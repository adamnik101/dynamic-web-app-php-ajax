window.addEventListener('DOMContentLoaded' , () => {
    window.onscroll = () => scrollNavChange()

    const navigation = document.getElementById('main-navigation-wrapper')
    let track = document.getElementById('track-name')
    const play = document.getElementById('play');
    let current = document.getElementById('current-time')
    let maxTimeShow = document.getElementById('max-time')
    let progress = document.getElementById('progress');
    let volumeRange = document.getElementById('volume-range');
    let icon = document.getElementById('volume-icon');
    let collectionLink = document.getElementById('collection-link');
    let mainWrapper = document.getElementById('main-wrapper');
    let bar = document.getElementsByClassName('bar');
    var ended = false;
    var isPlaying = false;
    var audio,songId, ao, userMovesProgress,timer, duration, minutesMax, secondsMax, currentMinute = 0, currentSecond = 0, secondsToCompare = 0, currentTime;
    var isMuted = false;
    let currentVolume;
    function scrollNavChange() {
        if(document.body.scrollTop > 100 || document.documentElement.scrollTop > 100){
            navigation.classList.add('nav-change-1')
            navigation.classList.remove('nav-change-0')
        }
        else {
            navigation.classList.add('nav-change-0')
            navigation.classList.remove('nav-change-1')
        }
    }
    function showSongInfo(data){
        isPlaying = true;
        track.innerHTML = ''
        let artist = document.createElement('p');
        let trackName = document.createElement('p');
        artist.style.color = '#535353'
        let artists =  data.artists.split(',');
        let owner, featuring = [];
        for (let item in artists){
            if(item > 0){
                featuring.push(artists[item]);
            }
            else{
                owner = artists[item]
            }
        }
        if(featuring.length){
            featuring.join(', ')
            owner += ' ft. ' + featuring
        }
        artist.innerHTML = owner;

        trackName.innerHTML = 'Now playing: ' + data.track;
        track.appendChild(trackName)
        track.appendChild(artist);
        duration = audio.duration
        minutesMax = Math.floor(Math.floor(duration) / 60)
        secondsMax = Math.floor(duration) % (minutesMax * 60)
        maxTimeShow.innerHTML = minutesMax + ":" + checkTime(secondsMax)
        play.classList.remove('fa-play-circle');
        play.classList.add('fa-pause-circle')
    }
    function bufferedAmount(audio){
        const bufferedAmount = Math.floor(audio.buffered.end(audio.buffered.length - 1));
        progress.style.setProperty('--buffered-width', `${(bufferedAmount / audio.duration) * 100}%`);
    }
    function startBarsAnimation(){
        for(let x of bar){
            x.style.animationIterationCount = 'infinite';
        }
    }
    function stopBarsAnimation(){
        for(let x of bar){
            x.style.animationIterationCount = '0';
        }
    }

    function playSong(data){
        if(data != undefined){
            songId = data.id;
            if(audio == undefined){
                let startTrack = new Promise((resolve, reject) => {
                    audio = new Audio(data.path);
                    resolve(audio.play());
                })
                .then(() => {
                    for(let x of bar){
                        x.style.animationIterationCount = 'infinite';
                    }
                    showSongInfo(data);
                    audio.onprogress = () => {
                        bufferedAmount(audio)
                        console.log(audio.buffered.end(0))
                    };
                    progress.max = audio.duration;
                })
            }
            else{
                let startTrack = new Promise((resolve, reject) => {
                    audio.src = data.path;
                    pauseCurrentSong();
                    resolve(audio.play());
                    progress.value = 0;
                    setRangeProgress(progress)
                    currentTime= 0, currentSecond= 0,currentMinute= 0, secondsToCompare = 0;
                })
                .then(() => {
                    for(let x of bar){
                        x.style.animationIterationCount = 'infinite';
                    }
                    showSongInfo(data);
                    progress.max = audio.duration;
                    audio.onprogress = () => {
                        bufferedAmount(audio)
                        console.log(audio.buffered.end(0))
                    };

                })
            }
        }
        setRangeProgress(progress);

        audio.addEventListener('timeupdate', handleTimeUpdate);
        audio.addEventListener('ended', () => {
            play.classList.remove('fa-play-circle', 'fa-pause-circle');
            play.classList.add('fa-redo-alt')
            ended = true;
            isPlaying = false;
            for(let x of bar){
                x.style.animationIterationCount = '0';
            }
        })
        /*timer = setInterval(() => {
            setRangeProgress(progress);
            secondsToCompare++
            currentTime = Math.round(audio.currentTime)
            if(currentTime >= 60 * (currentMinute + 1)){
                currentMinute++
                currentSecond = 0
            }
            progress.max = Math.round(duration);
            progress.value = (currentTime / duration) * Math.round(duration);

            current.innerHTML = currentMinute + ":" + checkTime(currentSecond++)
            if(secondsToCompare == Math.round(duration)){
                clearInterval(timer)
                currentTime= 0, currentSecond= 0,currentMinute= 0, secondsToCompare = 0;
                isPlaying = false;
                ended = true
                play.classList.remove('fa-pause');
                play.classList.add('fa-redo-alt')
            }

        }, 1000)*/
    }
    function handleTimeUpdate (){
        currentTime = Math.floor(audio.currentTime);
        currentMinute = Math.floor(currentTime / 60);
        duration = audio.duration;
        progress.value = currentTime;
        setRangeProgress(progress)

        if(currentTime < 60){
            current.innerHTML = currentMinute + ':' + checkTime(currentTime)
        }
        else{
            current.innerHTML = currentMinute + ':' + checkTime(currentTime % (60 * currentMinute));
        }
    }
    function checkTime(time){
        if(time < 10){
            time = '0' + time
        }
        return time;
    }
    function pauseCurrentSong(){
        if(songId == currentSongId){
            if(ended){
                setRangeProgress(progress);
                ended = false;
                audio.play()
                play.classList.remove('fa-play-circle', 'fa-redo-alt');
                play.classList.add('fa-pause-circle')
                current.innerHTML = "0:00"
                startBarsAnimation()
            }
            else if (isPlaying){
                audio.pause();
                isPlaying = false;
                //clearInterval(timer);
                play.classList.remove('fa-pause-circle');
                play.classList.add('fa-play-circle')
                stopBarsAnimation()
            }
            else{
                audio.play();
                isPlaying = true;
                playSong()
                play.classList.remove('fa-play-circle', 'fa-redo-alt');
                play.classList.add('fa-pause-circle')
                startBarsAnimation()
            }
        }
    }
    function muteVolume() {
        if(isMuted){
            audio.volume = currentVolume;
            isMuted = false;
            volumeRange.value = currentVolume * 100
            if(audio.volume > 0.5){
                icon.classList.add('fa-volume-up');
                icon.classList.remove('fa-volume-down', 'fa-volume-off', 'fa-volume-mute');
                startBarsAnimation()
            }
            else if(audio.volume > 0){
                icon.classList.add('fa-volume-down');
                icon.classList.remove('fa-volume-up', 'fa-volume-off', 'fa-volume-mute');
                startBarsAnimation()
            }
            else{
                icon.classList.add('fa-volume-off');
                icon.classList.remove('fa-volume-down', 'fa-volume-up', 'fa-volume-mute');
                stopBarsAnimation()
            }
        }
        else{
            currentVolume = audio.volume;
            audio.volume = 0;
            isMuted = true;
            icon.classList.add('fa-volume-mute');
            icon.classList.remove('fa-volume-down', 'fa-volume-off', 'fa-volume-up');
            volumeRange.value = audio.volume
            stopBarsAnimation()

        }
        setRangeProgress(volumeRange);
    }
    function setVolume(){
        audio.volume = volumeRange.value / 100;
        if(audio.volume > 0.5){
            icon.classList.add('fa-volume-up');
            icon.classList.remove('fa-volume-down', 'fa-volume-off', 'fa-volume-mute');
            startBarsAnimation()
        }
        else if(audio.volume > 0){
            icon.classList.add('fa-volume-down');
            icon.classList.remove('fa-volume-up', 'fa-volume-off', 'fa-volume-mute');
            startBarsAnimation()
        }
        else{
            icon.classList.add('fa-volume-off');
            icon.classList.remove('fa-volume-down', 'fa-volume-up', 'fa-volume-mute');
            stopBarsAnimation()
        }
        setRangeProgress(volumeRange);
    }
    function setRangeProgress(range){
        var min = range.min,
            max = range.max,
            val = range.value;
        range.style.backgroundSize = (val - min) * 100 / (max - min) + '% 100%';
    }
    let plays = document.getElementsByClassName('play')
    let currentSongId;
    function playSelectedSong(id){
        if(songId != currentSongId){
            $.ajax({
                url : 'models/playSong.php',
                type: 'get',
                data: {
                    id : currentSongId
                },
                success : (data) => {
                    playSong(JSON.parse(data)[0])
                    console.log(JSON.parse(data))
                },
                error : (err) => {
                    console.error(err);
                }
            })
        }
    }
    for(let x of plays){
        x.onclick = (e) => {
            currentSongId = e.currentTarget.dataset.id;
            playSelectedSong(currentSongId);
        }

    }
    play.addEventListener('click', () => {
        pauseCurrentSong();
    })

    progress.oninput = () => {
        //clearInterval(timer);
        let start = progress.value
        audio.removeEventListener('timeupdate', handleTimeUpdate);

        currentTime = Math.floor(progress.value);
        currentMinute = Math.floor(currentTime / 60);
        if(currentTime < 60){
            current.innerHTML = currentMinute + ':' + checkTime(currentTime)
        }
        else{
            current.innerHTML = currentMinute + ':' + checkTime(currentTime % (60 * currentMinute));
        }

        progress.onchange = () => {
            audio.currentTime = start
            console.log('changed')
            audio.addEventListener('timeupdate', handleTimeUpdate)
        }
        setRangeProgress(progress)
        /*setRangeProgress(progress);
        currentSecond = checkTime(currentSecond);
        current.innerHTML = currentMinute + ":" + currentSecond*/
    }
    volumeRange.oninput = () => {
        setVolume();
    }
    icon.onclick = () => {
        muteVolume();
    }
    collectionLink.onclick = () => {
        let page = 'collection';
        $.ajax({
            url: 'views/collection.php',
            type: 'get',
            data: {
                page : page
            },
            success: (data) => {
               displayPlaylists(data);
               console.log(data)
            },
            error: (err) => {
                console.error(err);
            }
        })
    }
    function ajaxGetById(url, id, callback){
        $.ajax({
            url: url,
            type: "GET",
            data: {
                id : id
            },
            dataType : "JSON",
            success: callback,
            error: (err) => {
                console.error(err)
            }
        })
    }
    function displayPlaylists(data){
        let displayPromise = new Promise((resolve, reject) => {
            let content = `<div class="playlists-wrapper">
            <h3>Your playlists</h3>
            <div class="playlists">`;
            for(let item of data){
                content += `
                    <div class="playlist" data-id="${item.id}">
                    <div class="playlist-image">
                        <i class="fab fa-itunes-note"></i>
                    </div>
                       <h5>${item.playlist}</h5>
                       <p>Author: ${item.username}</p>
                       <span><i class="fas fa-play"></i></span>
                    </div>`
            }
            content += `</div></div>`
            resolve(mainWrapper.innerHTML = content)
        })
        .then(() => {
            let playlists = document.getElementsByClassName('playlist');
            for (let playlist of playlists){
                playlist.onclick = (e) => {
                    let id = e.currentTarget.dataset.id;
                    ajaxGetById('views/playlistTracks.php', id, displayPlaylistTracks);
                }
            }
            function displayPlaylistTracks(data){
                let playlistTracksPromise = new Promise((resolve, reject) => {
                    let content = `<div class="playlist-tracks-wrapper">
                                        <p>Your playlist</p>
                                        <h3>${data[0].playlist}</h3>
                                        <table class="playlist-tracks">
                                        <thead>
                                            <tr> <th>#</th> <th>Track Title</th><th>Album</th></tr>
                                        </thead>
                                        <tbody>`;
                    let counter = 1;
                    for(let item of data){
                        content += `<tr class="playlist-track" data-id="${item.id}">
                                        <td class="counter">${counter++}</td>
                                        <td class="track-name">${item.track}</td>
                                        <td class="track-album">${item.album}</td>
                                    </tr>`
                    }
                    content += `</tbody></table></div>`
                    resolve(mainWrapper.innerHTML = content)
                })
                .then(() => {
                    let tracks = document.getElementsByClassName('playlist-track');
                    for (let track of tracks){
                        track.onclick = (e) => {
                            currentSongId = e.currentTarget.dataset.id;
                            playSelectedSong(currentSongId);
                            $(e.currentTarget).find('.track-name').css('color', "#5fba3d")
                        }
                    }
                })
            }
        })


    }
})