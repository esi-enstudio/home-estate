import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/App/**/*.php',
        './resources/views/filament/app/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './resources/views/components/**/*.blade.php',
        '../../../../vendor/awcodes/filament-tiptap-editor/resources/**/*.blade.php',
    ],
}
