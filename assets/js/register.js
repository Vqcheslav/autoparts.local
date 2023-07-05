document.getElementById('register-form').addEventListener('submit', async function (e) {
    e.preventDefault();
    let errorMsgDiv = document.getElementById('register-msg-div');

    errorMsgDiv.classList.add('d-none');

    let formData = new FormData(this);
    let response = await fetch('/api/register', {
        method: 'POST',
        body: formData,
        headers: {'Accept': 'application/json'}
    });
    let resultJson = await response.json();

    console.log(resultJson);

    if (resultJson.status !== 201) {
        errorMsgDiv.classList.remove('alert-success');
        errorMsgDiv.classList.add('alert-danger');
    } else {
        errorMsgDiv.classList.remove('alert-danger');
        errorMsgDiv.classList.add('alert-success');
    }

    errorMsgDiv.classList.remove('d-none');
    errorMsgDiv.textContent = resultJson.detail;
});