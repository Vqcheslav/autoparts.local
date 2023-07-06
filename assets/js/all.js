import {ToastMessage} from './classes';

document.querySelectorAll('.autopart-card-like-btn').forEach(function (element) {
    element.addEventListener('click', function () {
        let autopartId = this.dataset.autopartId;
        let userId = this.dataset.userId;

        if (! isUserLoggedIn(userId)) {
            return;
        }

        if (this.classList.toggle('liked')) {
            console.log('Added like to ' + autopartId);
        } else {
            console.log('Removed like to ' + autopartId);
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