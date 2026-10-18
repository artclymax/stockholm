// front/vite.config.js
import { defineConfig } from 'vite';
import path from 'path';
import FullReload from 'vite-plugin-full-reload';

// ПУТИ
const FRONT_DIR = path.resolve(__dirname);               // .../themes/wptest/front
const SRC_DIR = path.resolve(FRONT_DIR, 'src');       // .../themes/wptest/front/src
const THEME_DIR = path.resolve(FRONT_DIR, '..');        // .../themes/wptest

// glob к php-файлам (абсолютный), нормализуем слэши для chokidar на Windows
const PHP_GLOB = path
	.resolve(THEME_DIR, '**', '*.php')
	.replace(/\\/g, '/');

export default defineConfig(({ mode }) => {
	const isDev = mode === 'development';

	return {
		// Vite будет "root" = src (где лежат твои исходники)
		root: SRC_DIR,
		publicDir: path.resolve(__dirname, 'public'),
		server: {
			host: 'localhost',
			port: 5173,
			strictPort: true,
			cors: true,
			fs: {
				// Разрешаем доступ к файлам темы (выше root)
				allow: [FRONT_DIR, THEME_DIR]
			}
		},

		build: {
			//minify: false,        // отключает JS (esbuild)
			//cssMinify: false,     // отключает CSS минификацию (Vite 5+)
			sourcemap: false,
			// куда пушим финальные ассеты (относительно front/)
			outDir: path.resolve(THEME_DIR, 'assets'), // куда складываем файлы
			emptyOutDir: false,
			manifest: false,
			rollupOptions: {
				input: path.resolve(SRC_DIR, 'main.js'),
				output: {
					// фиксированные имена без хэшей
					entryFileNames: 'main.js',          // основной бандл -> main.js
					chunkFileNames: 'chunks/[name].js', // если появятся чанки — в папке chunks
					// assetFileNames: для css -> style.css, для прочих ассетов сохраняем имя
					assetFileNames: assetInfo => {
						const name = assetInfo.name || '';
						if (name.endsWith('.css')) return 'style.css';
						return 'assets/[name][extname]';
					},
					// попытка инлайнить динамические импорты в один файл
					inlineDynamicImports: true
				}
			}
		},

		plugins: [
			// FullReload слушает все php-файлы в теме и делает full page reload
			isDev && FullReload([PHP_GLOB])
		].filter(Boolean),

		resolve: {
			alias: {
				'@': SRC_DIR
			}
		},

		//css: {
		//	transformer: 'postcss',
		//	devSourcemap: true
		//}
	};
});

