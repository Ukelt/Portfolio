
let cogButton = document.getElementById('cog-dropdown');
cogButton.addEventListener('click', function () {
    let optionsContainer = document.getElementById('options-container');
        optionsContainer.classList.toggle('hidden');
});

// toggle the class change when the mouse leaves the cog button
