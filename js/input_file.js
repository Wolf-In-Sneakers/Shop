let fields = document.querySelectorAll('.input_file');
Array.prototype.forEach.call(fields, function (input) {
    let label = input.nextElementSibling,
        labelVal = label.querySelector('.input_file_empty').innerText;

    input.addEventListener('change', function (e) {
        let countFiles = '';
        if (this.files && this.files.length >= 1)
            countFiles = this.files.length;

        if (countFiles)
            label.querySelector('.input_file_empty').innerText = 'Выбрано файлов: ' + countFiles;
        else
            label.querySelector('.input_file_empty').innerText = labelVal;
    });
});