function isEmpty(object) {
    return Object
        .values(object)
        .every(val => typeof val === "undefined");
}

class Cookie
{
    static getCookie(key = '') {
        return document.cookie
            .split('; ')
            .find((row) => row.startsWith(key + '='))
            ?.split('=')[1];
    }

    static setCookie(
        key,
        value,
        maxAge = this.getTTL(),
        path = this.getPath()
    ) {
        document.cookie = `${key}=${value};max-age=${maxAge};path=${path};`;
    }

    static deleteCookie(
        key,
        path = this.getPath()
    ) {
        document.cookie = `${key}=;max-age=0;path=${path};`;
    }

    static getSameSite() {
        return `Strict`;
    }

    static getPath() {
        return '/';
    }

    static getTTL() {
        return 31536000;
    }
}

class Server
{
    static getQueryParameter(parameter) {
        let getParameters = new Proxy(new URLSearchParams(window.location.search), {
            get: (searchParams, prop) => searchParams.get(prop),
        });

        return getParameters[parameter];
    }

    static getAuthHeader() {
        return {
            'Authorization': `Bearer ${Server.getApiToken()}`,
        };
    };

    static getContentAcceptHeaders() {
        return {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        };
    }

    static getAuthContentAcceptHeaders() {
        return {
            'Authorization': `Bearer ${Server.getApiToken()}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        };
    }

    static getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').content;
    }

    static getApiToken() {
        return decodeURI(Cookie.getCookie('api_token'));
    }

    static async getData(
        uri = '',
        headers = {},
        withErrorHandling = true,
        onlyData = true
    ) {
        if (isEmpty(headers)) {
            headers = Server.getContentAcceptHeaders();
        }

        let url = window.location.origin + uri;
        let parameters = {
            method: 'GET',
            headers: headers,
            credentials: 'include',
        };

        let {response, parsedResponse} = await this.getResult(url, parameters);

        if (withErrorHandling) {
            let isOkResponse = this.checkResponse(await response, await parsedResponse);

            if (! Array.isArray(parsedResponse.data) && typeof parsedResponse.data !== 'object') {
                isOkResponse = false;

                Toast.showToastMessageWithTimeout(
                    'toast.error.error',
                    'toast.error.incorrect_response',
                    'error',
                    5
                );
            }

            if (! isOkResponse) {
                return false;
            }
        }

        if (onlyData) {
            return parsedResponse.data;
        }

        return parsedResponse;
    }

    static async getResult(url, parameters) {
        let response = await fetch(url, parameters);
        let isJson = response.headers.get('content-type')?.includes('application/json');
        let parsedResponse;

        if (isJson) {
            parsedResponse = await response.json();
        } else {
            parsedResponse = await response.text();
        }

        return {response, parsedResponse};
    }

    static async postData(
        uri = '',
        data = {},
        headers = {},
        withErrorHandling = true
    ) {
        if (isEmpty(headers)) {
            headers = Server.getContentAcceptHeaders();
        }

        let url = window.location.origin + uri;
        let parameters = {
            method: 'POST',
            headers: headers,
            body: data,
            credentials: 'include',
        };

        let {response, parsedResponse} = await this.getResult(url, parameters);

        if (withErrorHandling) {
            this.checkResponse(await response, await parsedResponse);
        }

        return parsedResponse;
    }

    static async putData(
        uri = '',
        data = {},
        headers = {},
        withErrorHandling = true
    ) {
        if (isEmpty(headers)) {
            headers = Server.getAuthContentAcceptHeaders();
        }

        let url = window.location.origin + uri;
        let parameters = {
            method: 'PUT',
            headers: headers,
            body: JSON.stringify(data),
            credentials: 'include',
        };

        let {response, parsedResponse} = await this.getResult(url, parameters);

        if (withErrorHandling) {
            this.checkResponse(await response, await parsedResponse);
        }

        return parsedResponse;
    }

    static async deleteData(
        uri = '',
        headers = {},
        withErrorHandling = true
    ) {
        if (isEmpty(headers)) {
            headers = Server.getContentAcceptHeaders();
        }

        let url = window.location.origin + uri;
        let parameters = {
            method: 'DELETE',
            headers: headers,
            body: JSON.stringify({
                '_token': Server.getCsrfToken(),
            }),
            credentials: 'include',
        };

        let {response, parsedResponse} = await this.getResult(url, parameters);

        if (withErrorHandling) {
            let isOkResponse = this.checkResponse(await response, await parsedResponse);

            if (isOkResponse) {
                Toast.showToastMessageWithTimeout(
                    'toast.success.success',
                    'toast.success.deleted',
                    'success'
                );
            }
        }

        return response;
    }

    static checkResponse(response, responseJson = {}) {
        if (response.ok) {
            return true;
        }

        let error = (responseJson && responseJson.message) || response.status;

        Toast.showServerError(error);

        return false;
    }
}

class Url
{
    static regexOfLocales = /\/(ru|en)\/*/;
    static arrayOfLocales = ['ru', 'en'];

    static getLanguage() {
        let resultOfRegex = Url.regexOfLocales.exec(window.location.href);

        if (resultOfRegex === null) {
            return 'en';
        }

        return resultOfRegex['1'];
    }

    static changeLanguage(language) {
        if (! Url.arrayOfLocales.includes(language)) {
            console.log('Undefined locale in changeLanguage function');

            return false;
        }

        Cookie.setCookie('locale', language);

        if (window.location.href.search(Url.regexOfLocales) === -1) {
            window.location.href = window.location.href.split('#')[0] + language;

            return true;
        }

        let resultOfRegex = Url.regexOfLocales.exec(window.location.href);

        window.location.href =
            window.location.href.substring(0, resultOfRegex.index)
            + '/'
            + language
            + window.location.href.substring(resultOfRegex.index + 3);

        return true;
    }

    static getRouteForApi(uri, parameters = '') {
        let result = `/${ Url.getLanguage() }/api/${ uri }`;

        if (parameters !== '') {
            result = `${ result }/${ parameters }`;
        }

        return result;
    }
}

class Storage
{
    static set(
        key,
        value,
        timestampWhenExpired = (new DateTime()).addHours(4).getTimestamp()
    ) {
        let cachedObject = {
            value: value,
            expiration: timestampWhenExpired
        };

        localStorage.setItem(key, JSON.stringify(cachedObject));
    }

    static get(key) {
        if (! this.exists(key)) {
            return '';
        }

        return this.getCachedObject(key).value;
    }

    static getCachedObject(key) {
        return JSON.parse(localStorage.getItem(key));
    }

    static exists(key) {
        let item = this.getCachedObject(key);

        if (Object.is(item, null)) {
            return false;
        }

        if (item.expiration < (new Date()).getTime()) {
            this.remove(key);
            return false;
        }

        return true;
    }

    static remove(key) {
        localStorage.removeItem(key);
    }

    static removeAll() {
        localStorage.clear();
    }
}

class DateTime
{
    dateTime;

    constructor(dateTime = '') {
        if (dateTime === '') {
            this.dateTime = new Date();
        } else {
            this.dateTime = new Date(dateTime);
        }
    }

    getDateObject() {
        return this.dateTime;
    }

    getTimestamp() {
        return this.dateTime.getTime();
    }

    addSeconds(seconds) {
        this.dateTime.setSeconds(
            this.dateTime.getSeconds() + seconds
        );

        return this;
    }

    addMinutes(minutes) {
        this.addSeconds(minutes * 60)

        return this;
    }

    addHours(hours) {
        this.addMinutes(hours * 60)

        return this;
    }

    addDays(days) {
        this.addHours(days * 24);

        return this;
    }

    addYears(years) {
        this.dateTime.setFullYear(
            this.dateTime.getFullYear() + years
        );

        return this;
    }

    toString() {
        return this.dateTime.toString();
    }
}

class Toast
{
    static getGeneralDiv() {
        return document.getElementById('toast-div');
    }

    static getMessageDiv() {
        return document.getElementById('toast-message-div');
    }

    static levelsOfMessage = [
        'message',
        'success',
        'warning',
        'error',
    ]

    static showToastMessageWithTimeout(
        title = 'toast.message.message',
        message = 'toast.message.default',
        level = 'message',
        hideAfterSeconds = 5,
        additionalInfo = ''
    ) {
        Toast.showToastMessage(title, message, level, additionalInfo);

        if (hideAfterSeconds > 0) {
            let timeout = hideAfterSeconds * 1000;

            setTimeout(
                Toast.hideToastMessageAfterTimeout,
                timeout,
                timeout
            );
        }
    }

    static showToastMessage(
        title = 'toast.message.message',
        message = 'toast.message.default',
        level = 'message',
        additionalInfo = ''
    ) {
        if (! this.levelsOfMessage.includes(level)) {
            return false;
        }

        // title = getToastMessage(title);
        message = message + ' ' + additionalInfo;

        Toast.getGeneralDiv().classList.remove('d-none-important');
        Toast.getGeneralDiv().dataset.time = (new Date()).getTime().toString();

        Toast.removeLevelsFromToastMessage();
        Toast.addClassesBeforeShowingToast();
        Toast.getMessageDiv().classList.add('toast-' + level);
        Toast.getMessageDiv().querySelector('.toast-message-p').innerHTML = `
            <span class="toast-title">${title}</span>
            ${message}
        `;
    }

    static hideToastMessage() {
        Toast.addClassesBeforeRemovingToast();

        setTimeout(() => {
            Toast.getGeneralDiv().classList.add('d-none-important');
            Toast.removeLevelsFromToastMessage();
        }, 600);
    }

    static hideToastMessageAfterTimeout(timeout) {
        let neededTimeToClose = Number(Toast.getGeneralDiv().dataset.time) + timeout;
        let nowTime = (new Date()).getTime();

        if (neededTimeToClose > nowTime) {
            return;
        }

        Toast.hideToastMessage();
    }

    static addClassesBeforeShowingToast() {
        Toast.getGeneralDiv().classList.remove('animation-disappear');
        Toast.getGeneralDiv().classList.add('animation-appear');
    }

    static addClassesBeforeRemovingToast() {
        Toast.getGeneralDiv().classList.remove('animation-appear');
        Toast.getGeneralDiv().classList.add('animation-disappear');
    }

    static removeLevelsFromToastMessage() {
        Toast.getMessageDiv().classList.remove(
            'toast-message',
            'toast-success',
            'toast-warning',
            'toast-error',
            'animation-appear',
            'animation-disappear',
        );
    }

    static showServerError(error = '404') {
        Toast.showToastMessageWithTimeout(
            'toast.error.error',
            'toast.error.incorrect_request',
            'error',
            5,
            error
        );
    }
}

class DomElement
{
    static addClassForElements(someClass, ...elements) {
        elements.forEach(function (element) {
            element.classList.add(someClass);
        });
    }

    static removeClassForElements(someClass, ...elements) {
        elements.forEach(function (element) {
            element.classList.remove(someClass);
        });
    }

    static show(...elements) {
        elements.forEach(function (element) {
            element.classList.remove('animation-disappear');
            element.classList.add('animation-appear');
            element.style = 'display: block !important;';
        });
    }

    static hideWithTimeout(...elements) {
        elements.forEach(function (element) {
            element.classList.remove('animation-appear');
            element.classList.add('animation-disappear');

            setTimeout(DomElement.hide, 600, element);
        });
    }

    static hide(...elements) {
        elements.forEach(function (element) {
            element.style = 'display: none !important;';
            element.classList.remove('animation-disappear');
        });
    }

    static hideVisibleWithTimeout(...elements) {
        elements.forEach(function (element) {
            element.classList.remove('animation-appear');
            element.classList.add('animation-disappear');

            setTimeout(DomElement.hideVisible, 600, element);
        });
    }

    static hideVisible(...elements) {
        elements.forEach(function (element) {
            if (window.getComputedStyle(element).display === 'none') {
                return;
            }

            element.style = 'display: block; visibility: hidden;';
        });
    }
}

class Siblings
{
    static getSibling(element, type = 'next') {
        if (type === 'previous') {
            return element.previousElementSibling;
        }

        return element.nextElementSibling;
    }

    static getSiblings(element, type = 'next', countOfSiblings = 999) {
        let siblings = [];

        function getSibling(element, countOfSiblings, i = 0) {
            let sibling = Siblings.getSibling(element, type);

            if (i < countOfSiblings && sibling !== null) {
                siblings.push(sibling);
                getSibling(sibling, countOfSiblings, i++);
            }

            return true;
        }

        getSibling(element, countOfSiblings);

        return siblings;
    }
}
