function restrictInput(event) {
    const input = event.target;
    let value = input.value;
    let cursorPos = input.selectionStart;

    // Remove invalid characters but allow digits and dot
    let newValue = '';
    let dotCount = 0;

    for (let i = 0; i < value.length; i++) {
        const char = value[i];
        if (char >= '0' && char <= '9') {
            newValue += char;
        } else if (char === '.' && dotCount === 0) {
            newValue += '.';
            dotCount++;
        } else {
            if (i < cursorPos) cursorPos--;
        }
    }

    // Restrict decimals to 3
    if (newValue.includes('.')) {
        const [intPart, decPart] = newValue.split('.');
        newValue = intPart + '.' + decPart.slice(0, 3);
    }

    // Only assign if different to avoid cursor jump
    if (newValue !== input.value) {
        input.value = newValue;
        // Correct cursor if necessary
        if (cursorPos > newValue.length) cursorPos = newValue.length;
        input.setSelectionRange(cursorPos, cursorPos);
    }
}

// On form submit
$('form').on('submit', function() {
    $('#saveBtn')
        .prop('disabled', true)
        .html('<i class="fa fa-spinner fa-spin"></i> Saving...');
});
