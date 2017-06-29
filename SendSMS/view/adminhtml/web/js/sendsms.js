document.onreadystatechange = function () {
    if (document.readyState == "interactive") {
        var max_chars = 160;
        var char_counts = document.getElementsByClassName('sendsms-char-count');
        for (var i = 0; i < char_counts.length; i++) {
            // find textfield
            var char_textfield = char_counts[i].parentNode.getElementsByTagName('textarea')[0];
            // set max length
            char_textfield.setAttribute('maxlength', max_chars);
            // count remaining characters
            char_counts[i].innerHTML = max_chars - char_textfield.value.length + ' caractere ramase';
            // add event
            char_textfield.onkeyup = function() {
                var text_length = this.value.length;
                var text_remaining = max_chars - text_length;
                this.parentNode.getElementsByClassName('sendsms-char-count')[0].innerHTML = text_remaining + ' caractere ramase';
            };
        }
    }
};
