<button {{ $attributes->merge(['type' => 'submit', 'class' => 'theme-button-primary']) }}>
    {{ $slot }}
</button>
