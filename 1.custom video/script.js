document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('customVideo');
    let isSeeking = false;
  

    video.addEventListener('dblclick', function (e) {
      const xPos = e.clientX / window.innerWidth;
      if (xPos > 0.5) {
        video.currentTime += 10;
      } else {
        video.currentTime -= 5;
      }
    });
  
   
    video.addEventListener('dblclick', function (e) {
      if (e.clientX / window.innerWidth > 0.3 && e.clientX / window.innerWidth < 0.7) {
        if (video.paused) {
          video.play();
        } else {
          video.pause();
        }
      }
    });
  
    
    video.addEventListener('mousedown', function (e) {
      if (e.clientX / window.innerWidth < 0.3) {
        isSeeking = true;
        video.playbackRate = 1;
      }
    });
  
    document.addEventListener('mouseup', function () {
      if (isSeeking) {
        video.playbackRate = 1;
        isSeeking = false;
      }
    });
  
    video.addEventListener('mousedown', function (e) {
      if (e.clientX / window.innerWidth > 0.7) {
        isSeeking = true;
        video.playbackRate = 2;
      }
    });
  
   
    document.addEventListener('mousemove', function (e) {
      if (isSeeking) {
        const xPos = e.clientX / window.innerWidth;
        video.currentTime = video.duration * xPos;
      }
    });
  });
  