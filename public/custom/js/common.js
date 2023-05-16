// set CSRF same as in Application/config/config.php.
const csrf_token_name = 'arcaSecurityCsrf';
const csrf_cookie_name = 'arcaCookieSecurityCsrf';

async function uploadApi(url, formID = null, idProgressBar = null, reloadFunction = null) {
	try {
		url = urls(url);
		var frm = $('#' + formID);
		const dataArr = new FormData(frm[0]);

		dataArr.append(csrf_token_name, Cookies.get(csrf_cookie_name)); // csrf

		// console.log('uploadApi Data : ', ...dataArr);

		var timeStarted = new Date().getTime();

		let axiosConfig = {
			headers: {
				"Authorization": `Bearer ${Cookies.get(csrf_cookie_name)}`,
				'X-Requested-With': 'XMLHttpRequest',
				'content-type': 'multipart/form-data',
				"X-CSRF-TOKEN": Cookies.get(csrf_cookie_name),
			},
			onUploadProgress: function (progressEvent) {
				if (idProgressBar != null) {
					const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);

					$('#' + idProgressBar).html(`
						<div class="col-12 mt-2 progress">
						<div id="componentProgressBarCanthink" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<div class="col-12 mt-2 mb-4">
						<div id="componentProgressBarStatusCanthink"></div>
						</div>
					`);

					$('#componentProgressBarCanthink').width(percentCompleted + '%');

					const disSize = SizeToText(progressEvent.total);
					const progress = progressEvent.loaded / progressEvent.total;
					const timeSpent = new Date().getTime() - timeStarted;
					const secondsRemaining = Math.round(((timeSpent / progress) - timeSpent) / 1000);

					let time;
					if (secondsRemaining >= 3600) {
						time = `${Math.floor(secondsRemaining / 3600)} hour ${Math.floor((secondsRemaining % 3600) / 60)} minute`;
					} else if (secondsRemaining >= 60) {
						time = `${Math.floor(secondsRemaining / 60)} minute ${secondsRemaining % 60} second`;
					} else {
						time = `${secondsRemaining} second(s)`;
					}

					$('#componentProgressBarStatusCanthink').html(`${SizeToText(progressEvent.loaded)} of ${disSize} | ${percentCompleted}% uploading <br> estimated time remaining: ${time}`);

					if (percentCompleted == 100) {
						$("#componentProgressBarCanthink").addClass("bg-success").removeClass("bg-info");
						setTimeout(function () {
							$('#componentProgressBarCanthink').width('0%');
							$('#componentProgressBarStatusCanthink').empty();
							$('#' + idProgressBar).empty();
						}, 500);
					} else if (percentCompleted > 0 && percentCompleted <= 40) {
						$("#componentProgressBarCanthink").addClass("bg-danger");
					} else if (percentCompleted > 40 && percentCompleted <= 60) {
						$("#componentProgressBarCanthink").addClass("bg-warning").removeClass("bg-danger");
					} else if (percentCompleted > 60 && percentCompleted <= 99) {
						$("#componentProgressBarCanthink").addClass("bg-info").removeClass("bg-warning");
					}
				}
			}
		};

		return axios.post(url, dataArr, axiosConfig)
			.then(function (res) {

				if (reloadFunction != null) {
					reloadFunction();
				}

				return res;
			})
			.catch(function (error) {

				if (error.response) {
					// Request made and server responded
					if (isError(error.response.status)) {
						noti(error.response.status, 'Something went wrong');
					} else if (isUnauthorized(error.response.status)) {
						noti(error.response.status, "Unauthorized: Access is denied");
					}
				} else if (error.request) {
					// The request was made but no response was received
					noti(500, 'Something went wrong');
				} else {
					// Something happened in setting up the request that triggered an Error
					console.log('Error', error.message);
					noti(500, 'Something went wrong');
				}

				// throw err;
			});

	} catch (e) {

		const res = e.response;
		console.log(e);
		console.log(res.status);
		console.log(res.message);

		if (isUnauthorized(res.status)) {
			noti(res.status, "Unauthorized: Access is denied");
		} else {
			noti(res.status, 'Something went wrong');
		}
	}
}

async function submitApi(url, dataObj, formID = null, reloadFunction = null, closedModal = true) {
	const submitBtnText = $('#submitBtn').html();

	var btnSubmitIDs = $('#' + formID + ' button[type=submit]').attr("id");
	var inputSubmitIDs = $('#' + formID + ' input[type=submit]').attr("id");
	var submitIdBtn = isDef(btnSubmitIDs) ? btnSubmitIDs : isDef(inputSubmitIDs) ? inputSubmitIDs : null;

	loadingBtn(submitIdBtn, true, submitBtnText);

	if (dataObj != null) {
		url = urls(url);

		try {
			var frm = $('#' + formID);
			const dataArr = new FormData(frm[0]);

			dataArr.append(csrf_token_name, Cookies.get(csrf_cookie_name)); // csrf

			return axios({
					method: 'POST',
					headers: {
						"Authorization": `Bearer ${Cookies.get(csrf_cookie_name)}`,
						'X-Requested-With': 'XMLHttpRequest',
						'content-type': 'application/x-www-form-urlencoded',
						"X-CSRF-TOKEN": Cookies.get(csrf_cookie_name),
					},
					url: url,
					data: dataArr
				})
				.then(result => {

					if (isSuccess(result.status) && reloadFunction != null) {
						reloadFunction();
					}

					if (formID != null) {
						if (closedModal) {
							var modalID = $('#' + formID).attr('data-modal');
							setTimeout(function () {
								if (modalID == '#generaloffcanvas-right') {
									$(modalID).offcanvas('toggle');
								} else {
									// $('#' + modalID).modal('hide');
									$(modalID).modal('hide');
								}
							}, 350);
						}
					}

					loadingBtn(submitIdBtn, false, submitBtnText);
					// noti(result.status, 'Save');
					return result;

				})
				.catch(error => {
					console.log('ERROR 1 SUBMIT API', error);

					let textMessage = isset(error.response.data.message) ? error.response.data.message : error.response.statusText;

					if (isError(error.response.status)) {
						noti(error.response.status, textMessage);
					} else if (isUnauthorized(error.response.status)) {
						noti(error.response.status, "Unauthorized: Access is denied");
					}

					loadingBtn(submitIdBtn, false);

					return error.response;
				});
		} catch (e) {
			const res = e.response;
			console.log('ERROR 2 Submit', res);
			loadingBtn(submitIdBtn, false);

			if (isUnauthorized(res.status)) {
				noti(res.status, "Unauthorized: Access is denied");
			} else {
				if (isError(res.status)) {
					var error_count = 0;
					for (var error in res.data.errors) {
						if (error_count == 0) {
							noti(res.status, res.data.errors[error][0]);
						}
						error_count++;
					}
				} else {
					noti(res.status, 'Something went wrong');
				}
				return res;
			}
		}
	} else {
		noti(400, "No data to insert!");
		loadingBtn('submitBtn', false);
	}
}

async function loginApi(url, dataObj, formID = null) {
	const submitBtnText = $('#loginBtn').html();

	var btnSubmitIDs = $('#' + formID + ' button[type=submit]').attr("id");
	var inputSubmitIDs = $('#' + formID + ' input[type=submit]').attr("id");
	var submitIdBtn = isDef(btnSubmitIDs) ? btnSubmitIDs : isDef(inputSubmitIDs) ? inputSubmitIDs : null;

	loadingBtn(submitIdBtn, true, submitBtnText);

	if (dataObj != null) {
		url = urls(url);

		try {
			var frm = $('#' + formID);
			const dataArr = new FormData(frm[0]);

			dataArr.append(csrf_token_name, Cookies.get(csrf_cookie_name)); // csrf

			return axios({
					method: 'POST',
					headers: {
						"Authorization": `Bearer ${Cookies.get(csrf_cookie_name)}`,
						'X-Requested-With': 'XMLHttpRequest',
						'content-type': 'application/x-www-form-urlencoded',
						"X-CSRF-TOKEN": Cookies.get(csrf_cookie_name),
					},
					url: url,
					data: dataArr
				})
				.then(result => {
					loadingBtn(submitIdBtn, false, submitBtnText);
					return result;
				})
				.catch(error => {
					const res = error.response;
					console.log('ERROR 1 LOGIN');

					if (isError(error.response.status)) {
						noti(error.response.status, error.response.data.message);
					} else if (isUnauthorized(error.response.status)) {
						noti(error.response.status, "Unauthorized: Access is denied");
					}

					loadingBtn(submitIdBtn, false, submitBtnText);
					throw error;
				});
		} catch (e) {
			const res = e.response;
			console.log('ERROR 2 LOGIN', res);
			loadingBtn(submitIdBtn, false, submitBtnText);

			if (isUnauthorized(res.status)) {
				noti(res.status, "Unauthorized: Access is denied");
			} else {
				if (isError(res.status)) {
					var error_count = 0;
					for (var error in res.data.errors) {
						if (error_count == 0) {
							noti(res.status, res.data.errors[error][0]);
						}
						error_count++;
					}
				} else {
					noti(res.status, 'Something went wrong');
				}
				return res;
			}
		}
	} else {
		loadingBtn(submitIdBtn, false, submitBtnText);
	}
}

async function deleteApi(id, url, reloadFunction = null) {
	if (id != '') {
		url = urls(url + '/' + id);
		try {
			return axios({
					method: 'DELETE',
					headers: {
						"Authorization": `Bearer ${Cookies.get(csrf_cookie_name)}`,
						'X-Requested-With': 'XMLHttpRequest',
						'content-type': 'application/x-www-form-urlencoded',
						"X-CSRF-TOKEN": Cookies.get(csrf_cookie_name),
					},
					url: url,
				})
				.then(result => {
					if (isSuccess(result.status) && reloadFunction != null) {
						reloadFunction();
					}
					noti(result.status, 'Remove');
					return result;
				})
				.catch(error => {
					if (isset(error.response.status)) {
						if (isError(error.response.status)) {
							noti(error.response.status);
						} else if (isUnauthorized(error.response.status)) {
							noti(error.response.status, "Unauthorized: Access is denied");
						}
					} else {
						console.log('delete api error : ', error);
					}
					throw error;
				});
		} catch (e) {
			const res = e.response;
			console.log('response : ', e);
			if (isUnauthorized(res.status)) {
				noti(res.status, "Unauthorized: Access is denied");
			} else {
				if (isError(res.status)) {
					var error_count = 0;
					for (var error in res.data.errors) {
						if (error_count == 0) {
							noti(res.status, res.data.errors[error][0]);
						}
						error_count++;
					}
				} else {
					noti(500, 'Something went wrong');
				}
				return res;
			}
		}
	} else {
		noti(400);
	}
}

async function callApi(method = 'POST', url, dataObj = null, option = {}) {
	url = urls(url);
	let dataSent = null;

	if (method == 'post' || method == 'put') {
		dataObj[csrf_token_name] = Cookies.get(csrf_cookie_name) // csrf token
		dataSent = new URLSearchParams(dataObj);
	}

	try {
		return axios({
					method: method,
					headers: {
						"Authorization": `Bearer ${Cookies.get(csrf_cookie_name)}`,
						'X-Requested-With': 'XMLHttpRequest',
						'content-type': 'application/x-www-form-urlencoded',
						"X-CSRF-TOKEN": Cookies.get(csrf_cookie_name),
					},
					url: url,
					data: dataSent,
				},
				option
			).then(result => {
				return result;
			})
			.catch(error => {
				console.log('ERROR 1 CALL API');
				let textMessage = isset(error.response.data.message) ? error.response.data.message : error.response.statusText;

				if (isError(error.response.status)) {
					noti(error.response.status, textMessage);
				} else if (isUnauthorized(error.response.status)) {
					noti(error.response.status, "Unauthorized: Access is denied");
				}

				return error.response;
			});
	} catch (e) {
		console.log('ERROR 2 CALL API');
		const res = e.response;
		if (isUnauthorized(res.status)) {
			noti(res.status, "Unauthorized: Access is denied");
		} else {
			if (isError(res.status)) {
				// var error_count = 0;
				// for (var error in res.data.errors) {
				// 	if (error_count == 0) {
				// 		noti(500, res.data.errors[error][0]);
				// 	}
				// 	error_count++;
				// }
				noti(res.response.status, res.response.data.message);
			} else {
				noti(500, 'Something went wrong');
			}
			return res;
		}
	}
}

function noti(code = 400, text = 'Something went wrong') {

	const apiStatus = {
		200: 'OK',
		201: 'Created', // POST/PUT resulted in a new resource, MUST include Location header
		202: 'Accepted', // request accepted for processing but not yet completed, might be disallowed later
		204: 'No Content', // DELETE/PUT fulfilled, MUST NOT include message-body
		301: 'Moved Permanently', // The URL of the requested resource has been changed permanently
		304: 'Not Modified', // If-Modified-Since, MUST include Date header
		400: 'Bad Request', // malformed syntax
		401: 'Unauthorized', // Indicates that the request requires user authentication information. The client MAY repeat the request with a suitable Authorization header field
		403: 'Forbidden', // unauthorized
		404: 'Not Found', // request URI does not exist
		405: 'Method Not Allowed', // HTTP method unavailable for URI, MUST include Allow header
		415: 'Unsupported Media Type', // unacceptable request payload format for resource and/or method
		426: 'Upgrade Required',
		429: 'Too Many Requests',
		451: 'Unavailable For Legal Reasons', // REDACTED
		500: 'Internal Server Error', // all other errors
		501: 'Not Implemented', // (currently) unsupported request method
		503: 'Service Unavailable' // The server is not ready to handle the request.
	};

	var resCode = typeof code === 'number' ? code : code.status;
	var textResponse = apiStatus[code];

	var messageText = isSuccess(resCode) ? ucfirst(text) + ' successfully' : isUnauthorized(resCode) ? 'Unauthorized: Access is denied' : isError(resCode) ? text : 'Something went wrong';
	var type = isSuccess(code) ? 'success' : 'error';
	var title = isSuccess(code) ? 'Great!' : 'Ops!';

	toastr.options = {
		"debug": false,
		"closeButton": !isMobileJs(),
		"newestOnTop": true,
		"progressBar": !isMobileJs(),
		"positionClass": !isMobileJs() ? "toast-top-right" : "toast-bottom-full-width",
		"preventDuplicates": isMobileJs(),
		"onclick": null,
		"showDuration": "300",
		"hideDuration": "1000",
		"timeOut": "5000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	}

	Command: toastr[type](messageText, title)

	return true;
}

function isMobileJs() {
	const toMatch = [
		/Android/i,
		/webOS/i,
		/iPhone/i,
		/iPad/i,
		/iPod/i,
		/BlackBerry/i,
		/Windows Phone/i
	];

	return toMatch.some((toMatchItem) => {
		return navigator.userAgent.match(toMatchItem);
	});
}

function log(inp) {
	if ($('pre').length) {
		$('pre').html(inp);
	} else {
		document.body.appendChild(document.createElement('pre')).innerHTML = syntaxHighlight(inp);
	}
}

function syntaxHighlight(json) {
	if (typeof json != 'string') {
		json = JSON.stringify(json, undefined, 2);
	}
	json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
	return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
		var cls = 'number';
		if (/^"/.test(match)) {
			if (/:$/.test(match)) {
				cls = 'key';
			} else {
				cls = 'string';
			}
		} else if (/true|false/.test(match)) {
			cls = 'boolean';
		} else if (/null/.test(match)) {
			cls = 'null';
		}
		return '<span class="' + cls + '">' + match + '</span>';
	});
}

function ucfirst(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}

function uppercase(obj) {
	obj.value = obj.value.toUpperCase();
	return obj.value;
}

function loadingBtn(id, display = false, text = "<i class='fa fa-save'></i> Save") {
	if (display) {
		$("#" + id).html('Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>');
		$("#" + id).attr('disabled', true);
	} else {
		$("#" + id).html(text);
		$("#" + id).attr('disabled', false);
	}
}

function printDiv(idToPrint, printBtnID = 'printBtn', printBtnText = "<i class='fa fa-save'></i> Save", pageTitlePrint = 'Print') {
	$("#" + idToPrint).printThis({
		// header: $('#headerPrint').html(),
		// footer: $('#tablePrint').html(), 
		importCSS: false,
		pageTitle: pageTitlePrint,
		beforePrint: loadingBtn(printBtnID, true),
	});

	setTimeout(function () {
		loadingBtn(printBtnID, false, printBtnText);
		$('#' + idToPrint).empty(); // reset
	}, 800);
}

function disableBtn(id, display = true, text = null) {
	$("#" + id).attr("disabled", display);
}

function showModal(id, timeSet = 0) {
	setTimeout(function () {
		$(id).modal('show');
	}, timeSet);
}

function closeModal(id, timeSet = 250) {
	setTimeout(function () {
		$(id).modal('hide');
	}, timeSet);
}

function closeOffcanvas(id, timeSet = 250) {
	setTimeout(function () {
		$(id).offcanvas('toggle');
	}, timeSet);
}

function isset(variable_name) {
	if (typeof variable_name !== 'undefined' && variable_name !== null) {
		return true;
	}

	return false;
}

function hasData(variable) {

	if (isDef(variable)) {
		return variable != '' && variable != null ? true : false;
	}

	return false;
}

function array_push(arrayData = null, newData = null) {
	return arrayData.push(newData);
}

function array_key_exists(arrKey = null, dataObj = null) {
	if (hasData(dataObj) && hasData(arrKey)) {
		if (dataObj.hasOwnProperty(arrKey)) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function implode(arrayData = null, delimiter = ',') {
	return arrayData.join(delimiter);
}

function explode(data = null, delimiter = ',') {
	return data.split(delimiter);
}

function isSuccess(res) {
	const successStatus = [200, 201, 302];
	const status = typeof res === 'number' ? res : res.status;
	return successStatus.includes(status);
}

function isError(res) {
	const errorStatus = [400, 404, 422, 429, 500, 503];
	const status = typeof res === 'number' ? res : res.status;
	return errorStatus.includes(status);
}

function isUnauthorized(res) {
	const unauthorizedStatus = [401, 403];
	const status = typeof res === 'number' ? res : res.status;
	return unauthorizedStatus.includes(status);
}

function trimData(text = null) {
	if (text != null && text != '')
		return typeof text === 'string' ? text.trim() : text;
	else
		return null;
}

// These helpers produce better VM code in JS engines due to their
// explicitness and function inlining.
function isUndef(v) {
	return v === undefined || v === null
}

function isDef(v) {
	return v !== undefined && v !== null
}

function isTrue(v) {
	return v === true
}

function isFalse(v) {
	return v === false
}

/**
 * Check if value is primitive.
 */
function isPrimitive(value) {
	return (
		typeof value === 'string' ||
		typeof value === 'number' ||
		// $flow-disable-line
		typeof value === 'symbol' ||
		typeof value === 'boolean'
	)
}

/**
 * Quick object check - this is primarily used to tell
 * Objects from primitive values when we know the value
 * is a JSON-compliant type.
 */
function isObject(obj) {
	return obj !== null && typeof obj === 'object'
}

/**
 * Get the raw type string of a value, e.g., [object Object].
 */
var _toString = Object.prototype.toString;

function toRawType(value) {
	return _toString.call(value).slice(8, -1)
}

/**
 * Strict object type check. Only returns true
 * for plain JavaScript objects.
 */
function isPlainObject(obj) {
	return _toString.call(obj) === '[object Object]'
}

function isRegExp(v) {
	return _toString.call(v) === '[object RegExp]'
}

/**
 * Check if val is a valid array index.
 */
function isValidArrayIndex(val) {
	var n = parseFloat(String(val));
	return n >= 0 && Math.floor(n) === n && isFinite(val)
}

function isPromise(val) {
	return (
		isDef(val) &&
		typeof val.then === 'function' &&
		typeof val.catch === 'function'
	)
}

function isArray(val) {
	return Array.isArray(val) ? true : false;
}

/**
 * Convert a value to a string that is actually rendered.
 */
function toString(val) {
	return val == null ?
		'' :
		Array.isArray(val) || (isPlainObject(val) && val.toString === _toString) ?
		JSON.stringify(val, null, 2) :
		String(val)
}

/**
 * Convert an input value to a number for persistence.
 * If the conversion fails, return original string.
 */
function toNumber(val) {
	var n = parseFloat(val);
	return isNaN(n) ? val : n
}

/**
 * Remove an item from an array.
 */
function remove(arr, item) {
	if (arr.length) {
		var index = arr.indexOf(item);
		if (index > -1) {
			return arr.splice(index, 1)
		}
	}
}

/**
 * Check whether an object has the property.
 */
var hasOwnProperty = Object.prototype.hasOwnProperty;

function hasOwn(obj, key) {
	return hasOwnProperty.call(obj, key)
}

/**
 * Create a cached version of a pure function.
 */
function cached(fn) {
	var cache = Object.create(null);
	return (function cachedFn(str) {
		var hit = cache[str];
		return hit || (cache[str] = fn(str))
	})
}

/**
 * Convert an Array-like object to a real Array.
 */
function toArray(list, start) {
	start = start || 0;
	var i = list.length - start;
	var ret = new Array(i);
	while (i--) {
		ret[i] = list[i + start];
	}
	return ret
}

/**
 * Merge an Array of Objects into a single Object.
 */
function toObject(arr) {
	var res = {};
	for (var i = 0; i < arr.length; i++) {
		if (arr[i]) {
			extend(res, arr[i]);
		}
	}
	return res
}

function isNumberKey(evt) {
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	return true;
}

function capitalize(str) {
	return str.toLowerCase().split(' ').map(function (word) {
		return word.replace(word[0], word[0].toUpperCase());
	}).join(' ');
}

function SizeToText(size) {
	var sizeContext = ["B", "KB", "MB", "GB", "TB"],
		atCont = 0;

	while (size / 1024 > 1) {
		size /= 1024;
		++atCont;
	}

	return Math.round(size * 100) / 100 + ' ' + sizeContext[atCont];
}

function refreshPage() {
	location.reload();
}

function noSelectDataLeft(text = 'Type', filesName = '5.png') {

	var fileImage = asset('custom/img/nodata/' + filesName);

	return "<div id='nodataSelect' class='col-lg-12 mb-4 mt-2'>\
            <center>\
                <img src='" + fileImage + "' class='img-fluid mb-3' width='38%'>\
                <h3 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;margin-bottom:15px'> \
                	<strong> NO " + text.toUpperCase() + " SELECTED </strong>\
                </h3>\
				<h6 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;font-size: 13px;'> \
					Select any " + text + " on the left\
				</h6>\
			</center>\
            </div>";
}

function nodata(text = true, filesName = '4.png') {

	var fileImage = asset('custom/img/nodata/' + filesName);
	var showText = (text) ? '' : 'style="display:none"';
	var suggestion = (text) ? '' : '"display:none!important"';

	return "<div id='nodata' class='col-lg-12 mb-4 mt-2'>\
            <center>\
                <img src='" + fileImage + "' class='img-fluid mb-3' width='38%'>\
                <h3 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;margin-bottom:15px'> \
                <strong> NO INFORMATION FOUND </strong>\
                </h3>\
                <h6 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;font-size: 13px;" + suggestion + "'> \
                    Here are some action suggestions for you to try :- \
                </h6>\
            </center>\
            <div class='row d-flex justify-content-center w-100' " + showText + ">\
                <div class='col-lg m-1 text-left' style='max-width: 350px !important;letter-spacing :1px; font-family: Quicksand, sans-serif !important;font-size: 12px;'>\
                    1. Try the registrar function (if any).<br>\
                    2. Change your word or search selection.<br>\
                    3. Contact the system support immediately.<br>\
                </div>\
            </div>\
            </div>";
}

function nodataAccess(filesName = '403.png') {

	var fileImage = asset('custom/img/nodata/' + filesName);
	return "<div id='nodataAccess' class='col-lg-12 mb-4 mt-2'>\
            <center>\
                <img src='" + fileImage + "' class='img-fluid mb-3' width='30%'>\
                <h3 style='letter-spacing :2px; font-family: Quicksand, sans-serif !important;margin-bottom:15px'> \
                <strong> NO INFORMATION FOUND </strong>\
                </h3>\
            </center>\
            </div>";
}

function isWeekend(date = new Date()) {
	return date.getDay() === 6 || date.getDay() === 0;
}

function getCurrentTime() {
	var today = new Date();
	var hh = today.getHours();

	if (hh < 10) {
		hh = '0' + hh
	}
	return hh + ":" + today.getMinutes() + ":" + today.getSeconds();
}

function getCurrentDate() {

	// Use Javascript
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth() + 1; //January is 0 so need to add 1 to make it 1!
	var yyyy = today.getFullYear();
	if (dd < 10) {
		dd = '0' + dd
	}
	if (mm < 10) {
		mm = '0' + mm
	}

	return yyyy + '-' + mm + '-' + dd;
}

function maxLengthCheck(object) {
	if (object.value.length > object.maxLength)
		object.value = object.value.slice(0, object.maxLength)
}

function isNumeric(evt) {
	var theEvent = evt || window.event;
	var key = theEvent.keyCode || theEvent.which;
	key = String.fromCharCode(key);
	var regex = /[0-9]|\./;
	if (!regex.test(key)) {
		theEvent.returnValue = false;
		if (theEvent.preventDefault) theEvent.preventDefault();
	}
}

function loading(id, display = false) {
	if (display) {
		$(id).block({
			message: '<div class="d-flex justify-content-center"> <div class="spinner-border text-light" role="status"></div> </div>',
			css: {
				backgroundColor: 'transparent',
				color: '#fff',
				border: '0'
			},
			overlayCSS: {
				opacity: 0.10
			}
		});
	} else {
		setTimeout(function () {
			$(id).unblock();
		}, 80);
	}
}

function chunkData(dataArr, perChunk) {
	if (perChunk <= 0) perChunk = 15;

	let result = [];
	for (let i = 0; i < dataArr.length / perChunk; i++) {
		result.push(dataArr.slice(i * perChunk, i * perChunk + perChunk));
	}
	return result;

}

function chunkDataObj(dataArr, chunk_size) {
	if (chunk_size <= 0) chunk_size = 15;

	const chunks = [];
	for (const cols = Object.entries(dataArr); cols.length;)
		chunks.push(cols.splice(0, chunk_size).reduce((o, [k, v]) => (o[k] = v, o), {}));

	return chunks;

}

function getDataPerChunk(total, percentage = 10) {
	var percent = (percentage / 100) * total;
	return Math.round(percent);
}

const distinct = (value, index, self) => {
	return self.indexOf(value) === index
}

const random = (min, max) => Math.floor(Math.random() * (max - min)) + min;

// SKELETON

function skeletonTable(hasFilter = null, buttonRefresh = true) {

	let totalData = random(3, 5);
	let body = '';

	for (let index = 0; index < totalData; index++) {
		body += '<tr>\
					<td width="5%" class="skeleton"> </td>\
					<td width="31%" class="skeleton"> </td>\
					<td width="25%" class="skeleton"> </td>\
					<td width="25%" class="skeleton"> </td>\
					<td width="14%" class="skeleton"> </td>\
				</tr>';
	}

	let filters = '';
	if (hasData(hasFilter)) {
		for (let index = 0; index < hasFilter; index++) {
			filters += '<select class="form-control form-control-sm float-end me-2 skeleton" style="width: 12%!important;"></select>';
		}
	}

	let buttonShow = buttonRefresh ? '<div class="col-xl-12 mb-4">\
										<div class="card ribbon-box border shadow-none mb-lg-0">\
										<div class="card-header text-muted">\
										<span class="ribbon ribbon-default ribbon-shape skeleton"><span> &nbsp;&nbsp;&nbsp;&nbsp; </span></span>\
											<button type="button" class="btn btn-default btn-sm float-end skeleton">  &nbsp;&nbsp;&nbsp; </button>\
											<button type="button" class="btn btn-default btn-sm float-end me-2 skeleton">\
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\
											</button>\
											' + filters + '\
										</div>' : '';

	return buttonShow + '		<div class="card-body">\
									<button type="button" class="btn btn-default btn-sm skeleton">  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </button>\
									<button type="button" class="btn btn-default btn-sm float-end skeleton mb-3">\
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\
									</button>\
									<div class="card-datatable">\
										<table class="table table-striped mt-3">\
											<tbody>' + body + '</tbody>\
										</table>\
									</div>\
								</div>\
							</div>\
						</div>';
}

function getImageDefault(imageName, path = 'public/upload/default/') {
	return urls(path + imageName);
}

function base_url() {
	// return window.location.protocol + "//" + window.location.host + "/";
	return $('meta[name="base_url"]').attr('content');
}

function urls(path) {
	const newpath = new URL(path, $('meta[name="base_url"]').attr('content'));
	return newpath.href;
}

function redirect(url) {
	const pathUrl = urls(url);
	window.location.replace(pathUrl);
	// window.location.href = pathUrl;
}

function asset(path, isPublic = true) {
	const publicFolder = isPublic ? 'public/' : '';
	return urls(publicFolder + path);
}
