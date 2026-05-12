<x-layouts.admin>
    <x-slot:title>Check-in QR</x-slot:title>
    <x-slot:header>Escaner QR</x-slot:header>

    <div class="space-y-8">
        <section class="glass-panel neon-outline p-8">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <p class="section-eyebrow">QR control</p>
                    <h1 class="mt-4 text-4xl font-black text-white">Check-in para {{ $evento->nombre }}</h1>
                    <p class="mt-3 max-w-2xl text-soft">Escanea el QR desde webcam o movil, valida el token y registra la asistencia en tiempo real.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.eventos.report', $evento) }}" class="theme-button-secondary">
                        <i class="bi bi-bar-chart"></i>
                        Ver reporte
                    </a>
                    <form action="{{ route('admin.eventos.reminders.store', $evento) }}" method="POST">
                        @csrf
                        <button type="submit" class="theme-button-secondary">
                            <i class="bi bi-envelope"></i>
                            Enviar recordatorios
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="glass-panel neon-outline p-8">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Camara de escaneo</h2>
                        <p class="mt-2 text-soft">Permite acceso a la camara para iniciar el reconocimiento del QR.</p>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" id="start-scan" class="theme-button-primary">
                            <i class="bi bi-camera-video"></i>
                            Iniciar
                        </button>
                        <button type="button" id="stop-scan" class="theme-button-danger">
                            <i class="bi bi-stop-circle"></i>
                            Detener
                        </button>
                    </div>
                </div>

                <div id="scanner-reader" class="scanner-shell mt-6"></div>

                <div class="mt-6 rounded-[1.5rem] border border-white/10 bg-slate-950/50 p-5">
                    <label for="manual-token" class="field-label">Ingreso manual del token</label>
                    <textarea id="manual-token" rows="4" class="field-textarea" placeholder="Pega aqui el token QR si deseas validar manualmente."></textarea>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <button type="button" id="manual-validate" class="theme-button-secondary">
                            <i class="bi bi-send-check"></i>
                            Validar token
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <section id="checkin-feedback" class="glass-panel neon-outline p-6">
                    <p class="section-eyebrow">Estado actual</p>
                    <h2 class="mt-4 text-2xl font-bold text-white">Esperando un escaneo</h2>
                    <p class="mt-3 text-soft">Cuando se procese un QR, aqui veras el resultado con mensajes claros en espanol.</p>
                </section>

                <section class="glass-panel neon-outline p-6">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="section-eyebrow">Ultimos accesos</p>
                            <h2 class="mt-4 text-2xl font-bold text-white">Check-ins recientes</h2>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        @forelse($recentCheckIns as $inscripcion)
                            <div class="rounded-[1.4rem] border border-white/10 bg-white/[0.03] p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-bold text-white">{{ $inscripcion->user->name }}</p>
                                        <p class="text-sm text-soft">{{ $inscripcion->user->email }}</p>
                                    </div>
                                    <span class="chip chip-success">{{ $inscripcion->check_in_at->format('H:i') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-[1.4rem] border border-dashed border-white/10 bg-white/[0.02] p-5 text-sm text-soft">
                                Aun no hay check-ins confirmados para este evento.
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </section>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        const scannerElementId = 'scanner-reader';
        const startButton = document.getElementById('start-scan');
        const stopButton = document.getElementById('stop-scan');
        const manualButton = document.getElementById('manual-validate');
        const manualInput = document.getElementById('manual-token');
        const feedbackCard = document.getElementById('checkin-feedback');
        const validateUrl = @json(route('admin.eventos.checkin.validate', $evento));
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        let scanner = null;
        let scannerRunning = false;
        let isSubmitting = false;

        function renderFeedback(type, title, message, extra = '') {
            const styles = {
                success: 'chip chip-success',
                error: 'chip chip-warning',
                info: 'chip chip-accent',
            };

            feedbackCard.innerHTML = `
                <p class="section-eyebrow">Resultado</p>
                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <span class="${styles[type] || styles.info}">${title}</span>
                </div>
                <p class="mt-4 text-lg font-bold text-white">${message}</p>
                ${extra ? `<div class="mt-4 rounded-[1.4rem] border border-white/10 bg-white/[0.03] p-4 text-sm text-soft">${extra}</div>` : ''}
            `;
        }

        function extractToken(rawValue) {
            const value = (rawValue || '').trim();

            if (value.startsWith('http://') || value.startsWith('https://')) {
                try {
                    const url = new URL(value);
                    return url.searchParams.get('token') || value;
                } catch (error) {
                    return value;
                }
            }

            return value;
        }

        async function submitToken(rawValue) {
            if (isSubmitting) {
                return;
            }

            const token = extractToken(rawValue);

            if (!token) {
                renderFeedback('error', 'Sin datos', 'Debes proporcionar un token QR valido.');
                return;
            }

            isSubmitting = true;

            try {
                const response = await fetch(validateUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ token }),
                });

                const data = await response.json();

                if (response.ok && data.ok) {
                    const extra = data.inscripcion
                        ? `<strong>${data.inscripcion.usuario}</strong><br>${data.inscripcion.email}<br>Registrado: ${data.inscripcion.check_in_at || 'ahora'}`
                        : '';
                    renderFeedback('success', 'Check-in exitoso', data.message, extra);
                    manualInput.value = '';
                    if (scannerRunning) {
                        await stopScanner();
                    }
                } else {
                    renderFeedback('error', 'Codigo rechazado', data.message || 'No fue posible validar el QR.');
                }
            } catch (error) {
                renderFeedback('error', 'Error tecnico', 'No se pudo completar la validacion del QR. Intenta nuevamente.');
            } finally {
                isSubmitting = false;
            }
        }

        async function startScanner() {
            if (scannerRunning) {
                return;
            }

            scanner = scanner || new Html5Qrcode(scannerElementId);

            try {
                await scanner.start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: { width: 260, height: 260 } },
                    async (decodedText) => {
                        await submitToken(decodedText);
                    }
                );
                scannerRunning = true;
                renderFeedback('info', 'Escaner activo', 'La camara esta lista para leer codigos QR.');
            } catch (error) {
                renderFeedback('error', 'Camara no disponible', 'No fue posible iniciar la camara. Usa el ingreso manual del token.');
            }
        }

        async function stopScanner() {
            if (!scanner || !scannerRunning) {
                return;
            }

            await scanner.stop();
            await scanner.clear();
            scannerRunning = false;
            renderFeedback('info', 'Escaner detenido', 'La camara fue detenida correctamente.');
        }

        startButton.addEventListener('click', startScanner);
        stopButton.addEventListener('click', stopScanner);
        manualButton.addEventListener('click', () => submitToken(manualInput.value));
    </script>
</x-layouts.admin>
