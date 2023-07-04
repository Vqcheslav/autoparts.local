document.getElementById('register-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    let formData = new FormData(this);
    let result = await fetch('/api/register', {
        method: 'POST',
        body: formData,
        headers: {
            'Content-Type': 'multipart/form-data',
            'Accept': 'application/json',
        }
    });
    let resultJson = await result.json();

    console.log(resultJson);
});