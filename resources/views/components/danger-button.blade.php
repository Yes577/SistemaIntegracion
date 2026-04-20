<button {{ $attributes->merge(['type' => 'submit', 'class' => 'theme-button-danger']) }}>
    {{ $slot }}
</button>
