document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("parse_xml_btn").addEventListener("click", function() {
        // Използвайте `fetch()` за изпращане на GET заявка
        fetch('/parser/parse_xml')
            .then(function(response) {
                // Уверете се, че заявката е успешна
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(function(data) {
//              console.log(data);
              const parsedData = JSON.parse(data);
              const parsedXmlAsText = parsedData.data.parsed_xml_as_text;
              console.log(parsedData['data']);
              let text = '';
              Object.values(parsedXmlAsText).forEach(file => {
                text += file.path + '<br>';
                text += file.content.replace(/</g, "&lt;").replace(/>/g, "&gt;") + '<br><br>';
              });
              document.getElementById("xml_text").innerHTML = text;
              document.getElementById("xml_text_modal").style.display = "block";
            })
            .catch(function(error) {
                // Обработка на грешки
                console.error('There has been a problem with your fetch operation:', error);
            });
    });

    // Затваряне на модалния прозорец
    var modal = document.getElementById("xml_text_modal");
    var span = document.getElementsByClassName("close")[0];

    span.onclick = function() {
        modal.style.display = "none";
    };

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
});