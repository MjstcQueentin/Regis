function resetAll() {
    document.querySelectorAll("audio").forEach(audio => {
        audio.pause();
        audio.currentTime = 0;
    });
}

document.querySelectorAll('button[data-trigger*="reset-all"]').forEach(button => {
    button.addEventListener("click", e => {
        resetAll();
    });
});