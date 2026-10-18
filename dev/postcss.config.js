export default {
	plugins: {
		autoprefixer: {},

		'postcss-pxtorem': {
			rootValue: 16,      // 1rem = 16px
			propList: ['*'],    // какие свойства конвертировать
			minPixelValue: 2,   // меньше 2px не трогать
			unitPrecision: 5    // точность
		}
	}
}