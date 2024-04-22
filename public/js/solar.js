document.addEventListener('DOMContentLoaded', () => {
    const planets = document.querySelectorAll('#data > a');
    const planetInfo = document.getElementById('planet-info');


    planets.forEach(planet => {
        planet.addEventListener('mouseenter', (event) => {
            let planetName = planet.title;
            event.target.classList.add('active')
            try {
                document.querySelector(`.orbit.${planet.title}-orbit`).classList.add('activeSolar')
            } catch (error) {

            }

            searchPhoto(planet.title, planetInfo)
        });

        planet.addEventListener('mouseleave', (event) => {
            planetInfo.style.display = 'none';
            event.target.classList.remove('active')
            document.querySelector(`.orbit.${planet.title}-orbit`).classList.remove('activeSolar')
        });
    });


tsParticles.load("tsparticles", {
    background: {
      color: "#000"
    },
    detectRetina: false,
    fpsLimit: 30,
    interactivity: {
      detectsOn: "canvas",
      events: {
        resize: true
      }
    },
    particles: {
      color: {
        value: "#fff"
      },
      number: {
        density: {
          enable: true,
          area: 1080
        },
        limit: 0,
        value: 400
      },
      opacity: {
        animation: {
          enable: true,
          minimumValue: 0.05,
          speed: 0.25,
          sync: false
        },
        random: {
          enable: true,
          minimumValue: 0.05
        },
        value: 1
      },
      shape: {
        type: "circle"
      },
      size: {
        random: {
          enable: true,
          minimumValue: 0.5
        },
        value: 1
      }
    }
  });
});

function searchPhoto(query, planetInfo) {
    let endpoint = `https://images-api.nasa.gov/search?q=${query}`

    axios.get(endpoint)
        .then(function (response) {
            try {
                let random_index = Math.floor(Math.random() * (response.data['collection']['items'].length - 0) + 0);
                let link = response.data['collection']['items'][random_index]['links'][0]['href'];
                let date = response.data['collection']['items'][random_index]['data'][0]['date_created'].split('T')[0]

                document.querySelector('#imgreturned').src = link
                document.querySelector('.date').textContent = date
                
                document.querySelector('#information').innerHTML = `
                    <li>Nom : ${response.data['collection']['items'][random_index]['data'][0]['title']}</li>
                    <li>Prise le : ${date}</li>
                    <li>Description : ${response.data['collection']['items'][random_index]['data'][0]['description']}</li>
                    `
                document.querySelector('.erreur').remove();
            } catch (error) {
                document.querySelector('#imgreturned').innerHTML = `<p>Aucune photo disponible pour ce nom</p>`
            }

            planetInfo.style.display = 'block';
        })
        .catch(function (error) {
            console.error('Une erreur s\'est produite', error);
        });

}

  