/*
 * 本地存储localstorage
 */
var mystorage = (function mystorage() {
	var set = function(key, value) {
		window.localStorage.setItem(key, JSON.stringify(value))
	};

	var get = function(key) {
		return JSON.parse(window.localStorage.getItem(key) || '[]')
	};

	var remove = function(key) {
		//清除对象
		window.localStorage.removeItem(key);
	};

	var clear = function() {
		//全部清除
		window.localStorage.clear();
	};
	return {
		set: set,
		get: get,
		remove: remove,
		clear: clear
	};
})();

/*
 * 				
 *	//存储和修改
	console.log(mystorage.set('tqtest','tqtestcontent'));
	//读取
	console.log(mystorage.get('tqtest'));
	//删除
	console.log(mystorage.remove('tqtest'));
	//全部清除
	mystorage.clear();
 */