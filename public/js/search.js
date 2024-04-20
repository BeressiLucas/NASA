document.addEventListener("DOMContentLoaded", function () {

    let urlQuery = window.location.pathname.split('/')[2];
    if (urlQuery != undefined) {
        document.querySelector('input').value = urlQuery;
    }

    document.querySelector('button[type=submit]').addEventListener('click', function () {
        query = document.querySelector('input').value;
        if (query.length === 0 && urlQuery == undefined) {
            query = 'Mars';
        } else {
            urlQuery = query; query = document.querySelector('input').value;
            if (query.length === 0 && urlQuery == undefined) {
                query = 'Mars';
            } else {
                urlQuery = query;
            }

        }

        searchPhoto(query);
    });


    function searchPhoto(query) {
        let endpoint = `https://images-api.nasa.gov/search?q=${query}`

        axios.get(endpoint)
            .then(function (response) {
                try {
                    random_index = Math.floor(Math.random() * (response.data['collection']['items'].length - 0) + 0);
                    link = response.data['collection']['items'][random_index]['links'][0]['href'];

                    document.querySelector('#image').src = link
                } catch (error) {
                    document.querySelector('#image').parentElement.innerHTML = `<p>Aucune photo disponible pour ce nom</p>`
                }
            })
            .catch(function (error) {
                console.error('Une erreur s\'est produite', error);
            });

    }

    //affiche default img
    searchPhoto('Mars');
});