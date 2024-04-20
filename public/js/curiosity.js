document.addEventListener("DOMContentLoaded", function () {

    document.querySelector('button[type=submit]').addEventListener('click', function () {
        time = document.querySelector('input').value;
        if (time.length === 0) {
            time = '2016-01-01';
        }

        curiosityPhoto(time);
    });


    function curiosityPhoto(time) {
        const APIKEY = 'kI4C91Nx5IyoT0Gmk4IWVPiErKci96rsfMlPKJCk'
        let endpoint = `https://api.nasa.gov/mars-photos/api/v1/rovers/curiosity/photos?earth_date=${time}&camera=NAVCAM&api_key=${APIKEY}`

        axios.get(endpoint)
            .then(function (response) {
                random_index = Math.floor(Math.random() * (response.data['photos'].length - 0) + 0);
                try {
                    link = response.data['photos'][random_index]['img_src']
                    document.querySelector('#image').src = link
                } catch (error) {
                    document.querySelector('#image').parentElement.innerHTML = `<p>Aucune photo disponible pour cette date</p>`
                }
            })
            .catch(function (error) {
                console.error('Une erreur s\'est produite', error);
            });
    }

    //affiche default img
    curiosityPhoto('2016-01-01');
});