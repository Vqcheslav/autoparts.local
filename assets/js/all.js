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
            console.log('Added like to ' + autopartId);

            let result = await Server.postData('/api/autoparts/favorites', data, Server.getContentAcceptHeaders())

            console.log(result);
            if (result.status === 201) {
                ToastMessage.showToastMessage(
                    'Успех',
                    'Товар добавлен в избранное',
                    'success'
                );
            } else {
                ToastMessage.showToastMessage(
                    'Ошибка',
                    'Не удалось добавить товар в избранное',
                    'error'
                );
            }
        } else {
            console.log('Removed like to ' + autopartId);

            let result = await Server.deleteData('/api/autoparts/favorites', data, Server.getContentAcceptHeaders())

            console.log(result);

            if (result.status === 204) {
                ToastMessage.showToastMessage(
                    'Успех',
                    'Товар удален из избранного',
                    'success'
                );
            } else {
                ToastMessage.showToastMessage(
                    'Ошибка',
                    'Не удалось удалить товар из избранного',
                    'error'
                );
            }
        }
    });
});

document.querySelectorAll('.autopart-card-cart-btn').forEach(function (element) {
    element.addEventListener('click', function () {
        let autopartId = this.dataset.autopartId;
        let userId = this.dataset.userId;

        if (! isUserLoggedIn(userId)) {
            return;
        }

        if (this.classList.toggle('in-cart')) {
            console.log('Added ' + autopartId + ' to cart');
        } else {
            console.log('Removed ' + autopartId + ' from cart');
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