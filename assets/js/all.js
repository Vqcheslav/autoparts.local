import {Server, ToastMessage} from './classes';

document.querySelectorAll('.autopart-card-like-btn').forEach(function (element) {
    element.addEventListener('click', async function () {
        let autopartId = this.dataset.autopartId;
        let userId = this.dataset.userId;
        let data = JSON.stringify({userId, autopartId});

        if (! isUserLoggedIn(userId)) {
            return;
        }

        if (this.classList.toggle('liked')) {
            let result = await Server.postData('/api/autoparts/favorites', data, Server.getContentAcceptHeaders())

            if (result.status === 201) {
                ToastMessage.showSuccessMessage(result.detail);
            } else {
                ToastMessage.showWarningMessage(result.detail);
            }
        } else {
            let result = await Server.deleteData('/api/autoparts/favorites', data, Server.getContentAcceptHeaders())

            if (result.status === 204) {
                ToastMessage.showSuccessMessage('Товар удален из избранного');
            } else {
                ToastMessage.showErrorMessage('Не удалось удалить товар из избранного');
            }
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

        if (this.classList.toggle('in-cart')) {
            let result = await Server.postData('/api/autoparts/carts', data, Server.getContentAcceptHeaders())

            if (result.status === 201) {
                ToastMessage.showSuccessMessage(result.detail);
            } else {
                ToastMessage.showWarningMessage(result.detail);
            }
        } else {
            let result = await Server.deleteData('/api/autoparts/carts', data, Server.getContentAcceptHeaders())

            if (result.status === 204) {
                ToastMessage.showSuccessMessage('Товар удален из корзины');
            } else {
                ToastMessage.showErrorMessage('Не удалось удалить товар из корзины');
            }
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