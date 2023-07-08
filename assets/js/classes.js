import {Toast} from "bootstrap";

function isEmpty(object) {
    return Object
        .values(object)
        .every(val => typeof val === "undefined");
}

export class Cookie
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

export class Server
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

    static getAcceptHeader() {
        return {
            'Accept': 'application/json',
        };
    }

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

                ToastMessage.showToastMessage(
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
                ToastMessage.showToastMessage(
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

        ToastMessage.showServerError(error);

        return false;
    }
}

export class Url
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

export class Storage
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

export class DateTime
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

export class ToastMessage
{
    static showToastMessage(
        title = 'Validation Error',
        message = 'Please check the entered data',
        level = 'message'
    ) {
        let toastDiv = document.querySelector('.toast');

        toastDiv.querySelector('.toast-level').classList.add('level-' + level);
        toastDiv.querySelector('.toast-title').innerText = title;
        toastDiv.querySelector('.toast-body').innerText = message;

        let bsToast = new Toast(toastDiv);

        bsToast.show();
    }

    static hideToastMessage() {
        let toastDiv = document.querySelector('.toast');
        let bsToast = new Toast(toastDiv);

        bsToast.hide();
    }

    static showServerError(error = '404') {
        this.showToastMessage(
            'Произошла ошибка',
            'Неверный запрос: ' + error,
            'error'
        );
    }
}

export class DomElement
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

export class Siblings
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
