import './bootstrap';

//お試し
import { createLaravelVitePlugin } from 'laravel-vite-plugin'
import 'laravel-vite-plugin/plugins/livewire'
import { Livewire } from 'livewire'

Livewire.start()

/* Livewireが自動でAlpainのアセットを呼び出すため不要
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()
*/