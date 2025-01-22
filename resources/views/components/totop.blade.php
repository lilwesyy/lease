<button onclick="topFunction()" id="myBtn" title="Go to top">
    <i class="fas fa-arrow-up"></i>
</button>

<style>
    html, body {
        scroll-behavior: smooth;
    }

    #myBtn {
        opacity: 0;
        position: fixed;
        bottom: 20px;
        right: 30px;
        z-index: 99;
        border: 2px solid transparent;
        outline: none;
        background-color: transparent;
        color: white;
        cursor: pointer;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 24px;
        transition: opacity .3s, background-color .3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #myBtn.show {
        opacity: 1;
    }

    #myBtn:hover {
        background-color: #555;
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let mybutton = document.getElementById("myBtn");

    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.classList.add("show");
            mybutton.style.borderColor = '{{ $color }}';
        } else {
            mybutton.classList.remove("show");
            mybutton.style.borderColor = 'transparent';
        }
    }

    window.topFunction = function() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
});
</script>
