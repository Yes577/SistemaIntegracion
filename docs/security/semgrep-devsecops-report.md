# Informe DevSecOps: SAST con Semgrep para Laravel

## A. Introduccion

El analisis estatico de seguridad (SAST) revisa el codigo fuente sin ejecutarlo para identificar patrones inseguros antes de que lleguen a produccion. En un flujo DevSecOps, SAST mueve la seguridad hacia etapas tempranas del ciclo de vida, reduce el costo de remediacion y evita que ramas criticas desplieguen cambios con riesgo conocido.

En este proyecto Laravel se integro Semgrep como motor SAST y GitHub Actions como orquestador CI/CD. La implementacion se adapta al uso de ramas `develop` y `main`, con politicas distintas de tolerancia al riesgo.

## B. Implementacion

### Componentes aplicados

- Workflow principal: `.github/workflows/semgrep.yml`
- Reglas personalizadas: `.semgrep/`
- Exclusiones de ruido: `.semgrepignore`
- Seeder endurecido: `database/seeders/DatabaseSeeder.php`
- Plantilla de variables seguras: `.env.example`

### Workflow completo de GitHub Actions

```yaml
name: Semgrep SAST

on:
  push:
    branches:
      - develop
      - main
  pull_request:
    branches:
      - develop
      - main

permissions:
  contents: read
  pull-requests: read
  security-events: write

jobs:
  semgrep:
    name: Semgrep (${{ github.event_name }})
    runs-on: ubuntu-latest
    timeout-minutes: 20
    env:
      FORCE_JAVASCRIPT_ACTIONS_TO_NODE24: "true"
      PIP_DISABLE_PIP_VERSION_CHECK: "1"
      SEMGREP_SEND_METRICS: "off"
      SEMGREP_SARIF_PATH: .semgrep-results/semgrep.sarif

    steps:
      - name: Checkout repository
        uses: actions/checkout@v6
        with:
          fetch-depth: 0

      - name: Determine branch policy
        id: policy
        shell: bash
        run: |
          echo "scan_mode=develop-full" >> "$GITHUB_OUTPUT"
          echo "baseline_ref=" >> "$GITHUB_OUTPUT"
          echo "gate_on_error=false" >> "$GITHUB_OUTPUT"

          if [[ "${GITHUB_EVENT_NAME}" == "pull_request" && "${GITHUB_BASE_REF}" == "develop" ]]; then
            echo "scan_mode=pr-develop-baseline" >> "$GITHUB_OUTPUT"
            echo "baseline_ref=origin/develop" >> "$GITHUB_OUTPUT"
          elif [[ "${GITHUB_EVENT_NAME}" == "pull_request" && "${GITHUB_BASE_REF}" == "main" ]]; then
            echo "scan_mode=pr-main-strict" >> "$GITHUB_OUTPUT"
            echo "gate_on_error=true" >> "$GITHUB_OUTPUT"
          elif [[ "${GITHUB_EVENT_NAME}" == "push" && "${GITHUB_REF_NAME}" == "main" ]]; then
            echo "scan_mode=main-full-strict" >> "$GITHUB_OUTPUT"
            echo "gate_on_error=true" >> "$GITHUB_OUTPUT"
          fi

      - name: Fetch develop baseline
        if: steps.policy.outputs.baseline_ref != ''
        shell: bash
        run: git fetch origin develop --depth=1

      - name: Set up Python
        uses: actions/setup-python@v6
        with:
          python-version: "3.12"

      - name: Install Semgrep
        shell: bash
        run: python -m pip install "semgrep>=1,<2"

      - name: Validate local Semgrep rules
        shell: bash
        run: semgrep --validate --config .semgrep

      - name: Run Semgrep and generate SARIF
        id: semgrep
        continue-on-error: true
        shell: bash
        env:
          BASELINE_REF: ${{ steps.policy.outputs.baseline_ref }}
        run: |
          EXTRA_ARGS=()
          if [[ -n "${BASELINE_REF}" ]]; then
            EXTRA_ARGS+=("--baseline-ref=${BASELINE_REF}")
          fi

          mkdir -p "$(dirname "${SEMGREP_SARIF_PATH}")"

          semgrep scan \
            --config p/ci \
            --config p/php \
            --config p/secrets \
            --config .semgrep \
            --sarif \
            --output "${SEMGREP_SARIF_PATH}" \
            "${EXTRA_ARGS[@]}" \
            .

      - name: Upload SARIF to GitHub Security
        if: always() && hashFiles('.semgrep-results/semgrep.sarif') != ''
        uses: github/codeql-action/upload-sarif@v4
        with:
          sarif_file: ${{ env.SEMGREP_SARIF_PATH }}
          category: semgrep-${{ steps.policy.outputs.scan_mode }}

      - name: Enforce ERROR findings on main
        if: steps.policy.outputs.gate_on_error == 'true'
        shell: bash
        env:
          BASELINE_REF: ${{ steps.policy.outputs.baseline_ref }}
        run: |
          EXTRA_ARGS=()
          if [[ -n "${BASELINE_REF}" ]]; then
            EXTRA_ARGS+=("--baseline-ref=${BASELINE_REF}")
          fi

          semgrep scan \
            --config p/ci \
            --config p/php \
            --config p/secrets \
            --config .semgrep \
            --severity ERROR \
            --error \
            "${EXTRA_ARGS[@]}" \
            .
```

### Explicacion del pipeline

1. `push` y `pull_request` sobre `develop` y `main` disparan el job.
2. `Determine branch policy` decide si el scan es completo, baseline o estricto.
3. `Fetch develop baseline` descarga `origin/develop` para PR hacia `develop`.
4. `Install Semgrep` instala la CLI en el runner Ubuntu.
5. `Validate local Semgrep rules` valida las reglas locales antes del escaneo para evitar fallos de configuracion.
6. `Run Semgrep and generate SARIF` ejecuta Semgrep con los packs y las reglas locales sobre la raiz del proyecto (`.`).
7. El reporte SARIF se genera en `.semgrep-results/semgrep.sarif`, carpeta excluida por `.semgrepignore`, para evitar falsos positivos de `p/secrets` sobre el propio artefacto.
8. `Upload SARIF to GitHub Security` publica los resultados en la pestaña Security de GitHub.
9. `Enforce ERROR findings on main` hace un segundo pase con `--severity ERROR --error` y bloquea builds criticas.

### Politicas por rama

- `push` a `develop`: full scan, no bloquea.
- `pull_request` hacia `develop`: baseline scan con `--baseline-ref=origin/develop`, solo findings nuevos, no bloquea.
- `push` a `main`: full scan estricto, bloquea si hay findings `ERROR`.
- `pull_request` hacia `main`: scan estricto, bloquea si hay findings `ERROR`.

### Packs y reglas personalizadas

Packs usados:

- `p/ci`
- `p/php`
- `p/secrets`
- `.semgrep` para reglas Laravel especificas del proyecto

Manejo de artefactos:

- El archivo SARIF del pipeline se escribe en `.semgrep-results/semgrep.sarif`.
- `.semgrepignore` excluye `semgrep.sarif`, `*.sarif` y `.semgrep-results/` para evitar que `p/secrets` analice artefactos de salida.

Nota de compatibilidad:

- Durante la validacion en GitHub Actions del 4 de mayo de 2026, `p/laravel` devolvio `exit code 7` al resolver la configuracion del ruleset.
- Para mantener el pipeline operativo y conservar cobertura sobre Laravel, la implementacion final usa `p/php` + reglas locales en `.semgrep/` para Mass Assignment, SQL Injection con consultas raw, Path Traversal, XSS en Blade y malas practicas de debugging.

Regla personalizada requerida para `eval()` y `dd()`:

```yaml
rules:
  - id: php.lang.security.no-eval
    message: Evita eval(); permite ejecucion dinamica de codigo y habilita RCE.
    severity: ERROR
    languages: [php]
    pattern: eval(...)

  - id: laravel.debug.no-dd-or-dump
    message: Elimina dd()/dump() del codigo versionado para evitar fuga de informacion y detencion del flujo.
    severity: WARNING
    languages: [php]
    pattern-either:
      - pattern: dd(...)
      - pattern: dump(...)
```

Regla usada para el hallazgo documentado:

```yaml
rules:
  - id: laravel.credentials.default-admin-password-seeder
    message: Seeder con password por defecto detectado. Usa variables de entorno o GitHub Secrets para bootstrap controlado.
    severity: ERROR
    languages: [php]
    pattern: Hash::make('admin123')
```

Reglas Laravel adicionales aplicadas en `.semgrep/custom-laravel-security.yml`:

```yaml
rules:
  - id: laravel.mass-assignment.request-all
    message: Uso directo de request()->all() en create/update detectado. Define fillable/guarded y valida campos permitidos.
    severity: ERROR
    languages: [php]
    pattern-either:
      - pattern: $MODEL::create($REQ->all())
      - pattern: $MODEL::forceCreate($REQ->all())
      - pattern: $MODEL->update($REQ->all())
      - pattern: $MODEL->fill($REQ->all())

  - id: laravel.sql-injection.raw-query-concat
    message: Consulta raw construida por concatenacion detectada. Usa query bindings en lugar de concatenar entrada.
    severity: ERROR
    languages: [php]
    pattern-regex: 'DB::(select|statement|unprepared|raw)\s*\(\s*[^)]*\.[^)]*\)'
```

### Cobertura de vulnerabilidades solicitada

- SQL Injection: `p/php`, `p/ci` y regla `laravel.sql-injection.raw-query-concat`
- Mass Assignment: regla `laravel.mass-assignment.request-all`
- Command Injection: `p/php` y regla `php.lang.security.no-shell-exec-like`
- Path Traversal: `p/php` y regla `laravel.path-traversal.user-controlled-path`
- Secrets hardcoded: `p/secrets` y regla `laravel.credentials.default-admin-password-seeder`
- Criptografia debil MD5: regla `php.crypto.security.no-md5`
- XSS en Blade: regla `blade.xss.no-raw-output`

## C. Identificacion de vulnerabilidades

### Hallazgo seleccionado

Se identifico un riesgo de credenciales por defecto hardcodeadas en el seeder principal.

### Codigo vulnerable detectado

```php
User::updateOrCreate([
    'email' => 'admin@admin.com',
], [
    'name' => 'Administrador',
    'password' => Hash::make('admin123'),
    'id_tipo_rol' => TipoRol::ADMIN_ID,
]);
```

### Por que es una vulnerabilidad

La contraseña del administrador estaba embebida en el repositorio. Aunque el valor se almacenaba hasheado en base de datos, el secreto original era conocido y podia replicarse en cualquier ambiente donde se ejecutaran los seeders.

### Riesgo de seguridad

- Reutilizacion de credenciales por defecto.
- Acceso administrativo no autorizado en entornos de prueba o preproduccion.
- Escalada de privilegios por conocimiento previo de la clave bootstrap.
- Incumplimiento de buenas practicas de gestion de secretos.

### Regla de Semgrep que lo detecta

- `laravel.credentials.default-admin-password-seeder`

## D. Correccion de la vulnerabilidad

### Codigo corregido

```php
$bootstrapAdminEmail = trim((string) env('ADMIN_BOOTSTRAP_EMAIL', ''));
$bootstrapAdminPassword = (string) env('ADMIN_BOOTSTRAP_PASSWORD', '');
$bootstrapAdminName = trim((string) env('ADMIN_BOOTSTRAP_NAME', 'Administrador'));

if ($bootstrapAdminEmail === '' || $bootstrapAdminPassword === '') {
    $this->command?->warn(
        'Bootstrap admin skipped: define ADMIN_BOOTSTRAP_EMAIL and ADMIN_BOOTSTRAP_PASSWORD before seeding.'
    );
    return;
}

User::updateOrCreate([
    'email' => $bootstrapAdminEmail,
], [
    'name' => $bootstrapAdminName,
    'password' => Hash::make($bootstrapAdminPassword),
    'id_tipo_rol' => TipoRol::ADMIN_ID,
]);
```

### Por que la solucion es segura

- El secreto deja de existir en el codigo fuente.
- El usuario admin solo se crea cuando el operador define credenciales explicitas.
- Las credenciales pueden inyectarse desde `.env` o desde secretos protegidos del pipeline.
- El bootstrap deja de depender de una contraseña predecible.

### Confirmacion del finding

La cadena `Hash::make('admin123')` ya no existe en `DatabaseSeeder.php`, por lo que la regla personalizada deja de hacer match sobre el archivo corregido.

## E. Resultados

### Comportamiento del pipeline

- En `develop`, el workflow genera SARIF y publica hallazgos sin bloquear el flujo.
- En PR hacia `develop`, el scan baseline reporta solo vulnerabilidades nuevas respecto a `origin/develop`.
- En `main`, un segundo pase con `--severity ERROR --error` actua como puerta de seguridad.
- En PR hacia `main`, el mismo criterio impide fusionar cambios con findings severos.

### Diferencia entre `develop` y `main`

- `develop` esta orientada a feedback rapido y no bloqueante.
- `main` se comporta como rama protegida con enforcement de hallazgos `ERROR`.

### Nota de ejecucion local

La validacion local nativa de Semgrep en este host Windows encontro un problema del motor RPC (`socketpair`) y Docker Desktop no estaba activo, por lo que la ruta reproducible recomendada es GitHub Actions sobre Ubuntu o Docker local con el daemon encendido. La implementacion del pipeline queda lista para ese escenario soportado.

## F. Ejecucion local con Docker

### Full scan

```powershell
docker run --rm `
  -e SEMGREP_SEND_METRICS=off `
  -v "${PWD}:/src" `
  -w /src `
  returntocorp/semgrep `
  semgrep scan `
    --config p/ci `
    --config p/php `
    --config p/secrets `
    --config .semgrep `
    --sarif `
    --output .semgrep-results/semgrep.sarif `
    .
```

### Baseline scan para PR hacia `develop`

```powershell
git fetch origin develop
docker run --rm `
  -e SEMGREP_SEND_METRICS=off `
  -v "${PWD}:/src" `
  -w /src `
  returntocorp/semgrep `
  semgrep scan `
    --config p/ci `
    --config p/php `
    --config p/secrets `
    --config .semgrep `
    --baseline-ref=origin/develop `
    --sarif `
    --output .semgrep-results/semgrep.sarif `
    .
```

## G. Ejemplo de salida SARIF

```json
{
  "version": "2.1.0",
  "runs": [
    {
      "tool": {
        "driver": {
          "name": "Semgrep",
          "rules": [
            {
              "id": "laravel.credentials.default-admin-password-seeder",
              "shortDescription": {
                "text": "Seeder con password por defecto detectado."
              }
            }
          ]
        }
      },
      "results": [
        {
          "ruleId": "laravel.credentials.default-admin-password-seeder",
          "level": "error",
          "message": {
            "text": "Seeder con password por defecto detectado. Usa variables de entorno o GitHub Secrets para bootstrap controlado."
          },
          "locations": [
            {
              "physicalLocation": {
                "artifactLocation": {
                  "uri": "database/seeders/DatabaseSeeder.php"
                },
                "region": {
                  "startLine": 25
                }
              }
            }
          ]
        }
      ]
    }
  ]
}
```

## H. Conclusion

La implementacion muestra que Semgrep puede integrarse de manera profesional en Laravel con politicas distintas por rama, baseline scan para PR a `develop`, reporte SARIF para GitHub Security y bloqueo selectivo solo en `main`.

El aprendizaje central es que seguridad y velocidad pueden convivir: `develop` recibe feedback temprano sin friccion, mientras `main` se protege con enforcement estricto. Esa combinacion es consistente con un enfoque DevSecOps maduro y sostenible.
