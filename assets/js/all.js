import {DomElement, Server, ToastMessage} from './classes';

document.querySelectorAll('.autopart-card-like-btn').forEach(function (element) {
    element.addEventListener('click', async function () {
        let autopartId = this.dataset.autopartId;
        let userId = this.dataset.userId;
        let data = JSON.stringify({userId, autopartId});

        if (! isUserLoggedIn(userId)) {
            return;
        }

        let result = await Server.postData('/api/autoparts/favorites', data, Server.getContentAcceptHeaders())

        if (result.data.added === true) {
            this.classList.add('liked');
        } else {
            this.classList.remove('liked');

            let autopartElement = document.querySelector(`.autopart[data-autopart-id="${autopartId}"]`);

            if (autopartElement.classList.contains('autopart-in-favorite')) {
                DomElement.hideWithTimeout(autopartElement);
            }
        }

        if (result.status === 200) {
            ToastMessage.showSuccessMessage(result.detail);
        } else {
            ToastMessage.showWarningMessage(result.detail);
        }
    });
});

document.querySelectorAll('.autopart-card-cart-btn').forEach(function (element) {
    element.addEventListener('click', async function () {
        let autopartId = this.dataset.autopartId;
        let userId = this.dataset.userId;
        let data = JSON.stringify({userId, autopartId});

        if (! isUserLoggedIn(userId)) {
            return;
        }

        let result = await Server.postData('/api/autoparts/carts', data, Server.getContentAcceptHeaders())

        if (result.data.added === true) {
            this.classList.add('in-cart');
        } else {
            this.classList.remove('in-cart');

            let autopartElement = document.querySelector(`.autopart[data-autopart-id="${autopartId}"]`);

            if (autopartElement.classList.contains('autopart-in-cart')) {
                DomElement.hideWithTimeout(autopartElement);
            }
        }

        if (result.status === 200) {
            ToastMessage.showSuccessMessage(result.detail);
        } else {
            ToastMessage.showWarningMessage(result.detail);
        }
    });
});

document.querySelectorAll('.autopart-card-buy-btn').forEach(function (element) {
    element.addEventListener('click', function () {
        let autopartId = this.dataset.autopartId;
        let userId = this.dataset.userId;

        if (! isUserLoggedIn(userId)) {
            return;
        }

        console.log('Bought ' + autopartId);
    });
});

function isUserLoggedIn(userId) {
    if (typeof userId === 'undefined') {
        ToastMessage.showToastMessage(
            'Предупреждение',
            'Необходимо войти в аккаунт',
            'warning'
        );

        return false;
    }

    return true;
}