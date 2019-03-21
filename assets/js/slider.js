window.addEventListener( 'DOMContentLoaded', function() {

  // Skip for old browsers
  if( ! ( 'classList' in document.createElement('div') ) ) {
    return;
  }

  var sliderContainer = document.querySelector('.gonzo-slider'),
      slides = document.querySelectorAll('.gonzo-slider .slide'),
      counter = 0,
      step = Math.ceil( sliderContainer.clientWidth / slides[0].clientWidth );
      next = step,
      activeSlide = null,
      activePage = null;

  sliderContainer.style.height = slides[counter].offsetHeight + 'px';

  if (!sliderContainer) return;
  if (!slides || slides.length < 3) return;

  function progressSlider(){
    if(activeSlide){
      activeSlide.classList.remove('active');
      activeSlide.classList.add('previous');
    }
    if(activePage){
      activePage.classList.remove('active');
      activePage.blur();
    }
    slides[counter].classList.remove('next');
    slides[counter].classList.remove('previous');
    slides[counter].classList.add('active');
    pages[counter].classList.add('active');
    activeSlide = slides[counter];
    activePage = pages[counter];
    counter = counter + step;
    if(counter >= slides.length){
      counter = 0;
    }
    setTimeout(function(){
      slides[counter].classList.add('hide');
      slides[counter].classList.remove('previous');
      slides[counter].classList.add('next');
      slides[counter].classList.remove('hide');
    }, 320);
  }

  //sliderContainer.style.height = slides[0].offsetHeight + 'px';
  sliderContainer.className = sliderContainer.className + ' running';

  var pager = document.createElement('ul');
  for( var i = 0, sl = slides.length; i < sl; i++ ) {
    var page = document.createElement('li');
    page.innerHTML = '<a href="#' + slides[i].id + '">' + slides[i].id + '</a>';
    pager.appendChild(page);
  }

  var pages = pager.querySelectorAll('a');
  for( var i = 0, pl = pages.length; i < pl; i++ ) {
    pages[i].addEventListener('click', function(e){
      for(var i = 0, l = slides.length; i < l; i++){
        slides[i].classList.remove('active');
        slides[i].classList.remove('next');
        slides[i].classList.remove('previous');
      };
      nextSlide = document.getElementById(this.hash.replace('#', ''));
      counter = Array.prototype.indexOf.call( slides, nextSlide );
      slides[counter].classList.add('next');
      clearInterval(sliderInterval);
      sliderInterval = null;
      progressSlider();
      if(!sliderInterval){
        sliderInterval = setInterval(progressSlider, 5000);
      }
      e.preventDefault();
    }, false);
  }

  sliderContainer.appendChild( pager );

  progressSlider();

  window.sliderInterval = setInterval(progressSlider, 5000);

  var touchstartX = 0,
      touchstartY = 0,
      touchendX = 0,
      touchendY = 0,
      touchX = 0,
      touchY = 0,
      other = 0,
      areawidth = sliderContainer.clientWidth;

  sliderContainer.addEventListener('touchstart', function(event) {
    touchstartX = event.changedTouches[0].screenX;
    touchstartY = event.changedTouches[0].screenY;
  }, false);

  sliderContainer.addEventListener('touchmove', function(event) {
    touchX = event.changedTouches[0].screenX;
    touchY = event.changedTouches[0].screenY;
    var distance = {x: touchX - touchstartX, y: touchY - touchstartY};
    if( Math.abs(touchX - touchstartX) > 20 ){
      handleDrag(event);
    }
  }, false);

  sliderContainer.addEventListener('touchend', function(event) {
    touchendX = event.changedTouches[0].screenX;
    touchendY = event.changedTouches[0].screenY;
    handleGesure(event);
  }, false);

  function handleGesure(event) {
    var dragmargin = areawidth / 4;
    activeSlide.classList.remove('dragged');
    slides[other].classList.remove('dragged');
    var current = Array.prototype.indexOf.call( slides, activeSlide );
    if( Math.abs(touchX - touchstartX) > dragmargin ){
      if(touchX - touchstartX < 0){
        counter = current + 1;
        if (counter == slides.length){
          counter = 0;
        }
        activeSlide.style.transform = 'translateX(-100vw)';
      }
      else {
        counter = current - 1;
        if(counter < 0){
          counter = slides.length - 1;
        }
        activeSlide.style.transform = 'translateX(100vw)';
      }
      slides[other].style.transform = 'translateX(0)';
      slides[other].style.zIndex = 5;
      setTimeout(function(){
        progressSlider();
        for(var i = 0, l = slides.length; i < l; i++){
          slides[i].removeAttribute('style');
        };
        if(!sliderInterval) {
          sliderInterval = setInterval(progressSlider, 5000);
        }
      }, 300);
      return;
    }
    if(!sliderInterval) {
      sliderInterval = setInterval(progressSlider, 5000);
    }
    for(var i = 0, l = slides.length; i < l; i++){
      slides[i].removeAttribute('style');
    };
  }

  function handleDrag(event) {
    var distance = {x: touchX - touchstartX, y: touchY - touchstartY};
    if(distance.x > 0){
      var direction = 'right';
    }
    else if(distance.x < 0) {
      var direction = 'left';
    }
    if(direction){
      event.preventDefault();
      clearInterval(sliderInterval);
      sliderInterval = null;
      activeSlide.classList.add('dragged');
      activeSlide.style.transform = 'translateX(' + distance.x + 'px)';
      var current = Array.prototype.indexOf.call( slides, activeSlide );
      if(direction == 'left'){
        other = current + 1;
        if (other == slides.length){
          other = 0;
        }
        var translate = 'calc(100vw - ' + Math.abs(distance.x) + 'px)';
      }
      else {
        other = current - 1;
        if(other < 0){
          other = slides.length - 1;
        }
        var translate = 'calc(' + Math.abs(distance.x) + 'px - 100vw)';
      }
      slides[other].classList.add('dragged');
      slides[other].style.transform = 'translateX( calc(' + translate + '))';
    }
  }


});
