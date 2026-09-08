<!-- Preloader HTML -->
<div id="preloader">
    <div class="spinner"></div>
</div>

<!-- Preloader CSS -->
<style>
    #preloader {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background-color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #ccc;
        border-top: 5px solid #1d72b8;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
