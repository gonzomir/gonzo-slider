window.addEventListener( 'DOMContentLoaded', function() {

  // Skip for old browsers
  if( ! ( 'classList' in document.createElement('div') ) ) {
    return;
  }

  var sliderContainer = document.querySelector('.gonzo-slider'),
      slides = document.querySelectorAll('.gonzo-slider .slide');

  if ( ! sliderContainer ) {
    return;
  }
  if ( ! slides || slides.length < step * 3 ) {
    return;
  }

  var counter = 0,
      step = Math.ceil( sliderContainer.clientWidth / slides[0].clientWidth );
      next = step,
      activeSlide = null,
      activePage = null,
      interval = sliderContainer.dataset.interval || 5000,
      hasPager = sliderContainer.dataset.pager || false,
      hasNav = sliderContainer.dataset.nav || false,
      sliderInterval = null;

  function slideForward() {
    if ( activeSlide ) {
      activeSlide.classList.remove( 'active' );
      activeSlide.classList.add( 'previous' );
    }

    slides[counter].classList.remove( 'next' );
    slides[counter].classList.remove( 'previous' );
    slides[counter].classList.add( 'active' );
    activeSlide = slides[counter];

    if ( hasPager ) {
      if ( activePage ) {
        activePage.classList.remove( 'active' );
        activePage.blur();
      }
      pages[counter].classList.add( 'active' );
      activePage = pages[counter];
    }

    counter = counter + step;
    if ( counter >= slides.length ) {
      counter = 0;
    }

    setTimeout( function(){
      slides[counter].classList.add( 'hide' );
      var temp = slides[counter].clientWidth;
      slides[counter].classList.remove( 'previous' );
      slides[counter].classList.add( 'next' );
      slides[counter].classList.remove( 'hide' );
    }, 320 );
  }

  function slideBack() {
    current = 0;
    if ( activeSlide ) {
      current = Array.prototype.indexOf.call( slides, activeSlide );
    }
    counter = current - step;
    if ( counter < 0 ) {
      counter = slides.length - step;
    }

    slides[counter].classList.add( 'hide' );
    var temp = slides[counter].clientWidth;
    slides[counter].classList.remove( 'next' );
    slides[counter].classList.add( 'previous' );
    slides[counter].classList.remove( 'hide' );
    var temp = slides[counter].clientWidth;

    setTimeout( function() {
      if ( activeSlide ) {
        activeSlide.classList.remove( 'active' );
        activeSlide.classList.add( 'next' );
      }

      slides[counter].classList.remove( 'next' );
      slides[counter].classList.remove( 'previous' );
      slides[counter].classList.add( 'active' );
      activeSlide = slides[counter];

      if ( hasPager ) {
        if ( activePage ) {
          activePage.classList.remove( 'active' );
          activePage.blur();
        }
        pages[counter].classList.add( 'active' );
        activePage = pages[counter];
      }

      previous = counter - step;
      if ( previous < 0 ) {
        previous = slides.length - step;
      }
      setTimeout( function(){
        slides[previous].classList.add( 'hide' );
        var temp = slides[previous].clientWidth;
        slides[previous].classList.remove( 'next' );
        slides[previous].classList.add( 'previous' );
        slides[previous].classList.remove( 'hide' );
      }, 320 );

      counter = counter + step;
      if ( counter >= slides.length ) {
        counter = 0;
      }
    }, 0 );
  }

  function setupPager() {
    var pager = document.createElement('ul');
    pager.className = 'gonzo-slider-pager';
    for( var i = 0, sl = slides.length; i < sl; i++ ) {
      var page = document.createElement('li');
      page.innerHTML = '<a href="#' + slides[i].id + '">' + slides[i].id + '</a>';
      pager.appendChild(page);
    }

    var pages = pager.querySelectorAll('a');
    for( var i = 0, pl = pages.length; i < pl; i++ ) {
      pages[i].addEventListener( 'click', function( e ) {
        for(var i = 0, l = slides.length; i < l; i++){
          slides[i].classList.remove('active');
          slides[i].classList.remove('next');
          slides[i].classList.remove('previous');
        };
        nextSlide = document.getElementById( this.hash.replace( '#', '' ) );
        counter = Array.prototype.indexOf.call( slides, nextSlide );
        slides[counter].classList.add( 'next' );
        clearInterval( sliderInterval );
        sliderInterval = null;
        slideForward();
        if ( ! sliderInterval ) {
          sliderInterval = setInterval( slideForward, interval );
        }
        e.preventDefault();
      }, false );
    }

    sliderContainer.appendChild( pager );
  }

  function setupNavigation() {
    var nav = document.createElement( 'ul' );
    nav.className = 'gonzo-slider-nav';

    var prev = document.createElement( 'li' );
    prev.innerHTML = '<button type="button" id="slider-prev"></button>';
    nav.appendChild(prev);

    var next = document.createElement( 'li' );
    next.innerHTML = '<button type="button" id="slider-next"></button>';
    nav.appendChild( next );

    prev.querySelector('button').addEventListener( 'click', function( e ) {
      clearInterval( sliderInterval );
      sliderInterval = null;
      slideBack();
      if ( ! sliderInterval ) {
        sliderInterval = setInterval( slideForward, interval );
      }
    } );

    next.querySelector('button').addEventListener( 'click', function( e ) {
      clearInterval( sliderInterval );
      sliderInterval = null;
      slideForward();
      if ( ! sliderInterval ) {
        sliderInterval = setInterval( slideForward, interval );
      }
    } );

    sliderContainer.appendChild( nav );
  }

  sliderContainer.style.height = slides[0].offsetHeight + 'px';
  sliderContainer.className = sliderContainer.className + ' running';

  if ( hasPager ) {
    setupPager();
  }

  if ( hasNav ) {
    setupNavigation();
  }

  slideForward();

  window.sliderInterval = setInterval(slideForward, interval);

  var touchstartX = 0,
      touchstartY = 0,
      touchendX = 0,
      touchendY = 0,
      touchX = 0,
      touchY = 0,
      other = 0,
      areawidth = sliderContainer.clientWidth;

  sliderContainer.addEventListener( 'touchstart', function( event ) {
    touchstartX = event.changedTouches[0].screenX;
    touchstartY = event.changedTouches[0].screenY;
  }, false);

  sliderContainer.addEventListener( 'touchmove', function( event ) {
    touchX = event.changedTouches[0].screenX;
    touchY = event.changedTouches[0].screenY;
    var distance = {x: touchX - touchstartX, y: touchY - touchstartY};
    if ( Math.abs( touchX - touchstartX ) > 20 ) {
      handleDrag( event );
    }
  }, false);

  sliderContainer.addEventListener( 'touchend', function( event ) {
    touchendX = event.changedTouches[0].screenX;
    touchendY = event.changedTouches[0].screenY;
    handleGesure( event );
  }, false);

  function handleGesure( event ) {
    var dragmargin = areawidth / 4;
    activeSlide.classList.remove( 'dragged' );
    slides[other].classList.remove( 'dragged' );
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
      setTimeout( function() {
        slideForward();
        for ( var i = 0, l = slides.length; i < l; i++ ) {
          slides[i].removeAttribute( 'style' );
        };
        if ( ! sliderInterval ) {
          sliderInterval = setInterval( slideForward, interval );
        }
      }, 300 );
      return;
    }
    if ( ! sliderInterval ) {
      sliderInterval = setInterval( slideForward, interval );
    }
    for ( var i = 0, l = slides.length; i < l; i++ ) {
      slides[i].removeAttribute( 'style' );
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
      clearInterval( sliderInterval );
      sliderInterval = null;
      activeSlide.classList.add( 'dragged' );
      activeSlide.style.transform = 'translateX(' + distance.x + 'px)';
      var current = Array.prototype.indexOf.call( slides, activeSlide );
      if ( direction == 'left' ) {
        other = current + 1;
        if ( other == slides.length ) {
          other = 0;
        }
        var translate = 'calc(100% - ' + Math.abs( distance.x ) + 'px)';
      }
      else {
        other = current - 1;
        if(other < 0){
          other = slides.length - 1;
        }
        var translate = 'calc(' + Math.abs( distance.x ) + 'px - 100%)';
      }
      slides[other].classList.add( 'dragged' );
      slides[other].style.transform = 'translateX( calc(' + translate + '))';
    }
  }


});
