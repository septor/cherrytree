function toggleMenu() {
    var nav = document.querySelector('nav');
    nav.style.display = nav.style.display === 'block' ? '' : 'block';
    var menuToggle = document.querySelector('.menu-toggle');
    menuToggle.classList.toggle('active');
}


var submenuItems = document.querySelectorAll('nav ul li ul');
for (var i = 0; i < submenuItems.length; i++) {
    submenuItems[i].style.display = 'none';
    submenuItems[i].parentNode.querySelector('a').addEventListener('click', function(e) {
        e.preventDefault();
        var submenu = this.parentNode.querySelector('ul');
        submenu.style.display = submenu.style.display === 'block' ? '' : 'block';
    });
}