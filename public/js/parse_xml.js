document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("xml_parse_btn").addEventListener("click", function() {
      const errorsDiv = document.getElementById('parser_errors');
      
      let xmlText = function () {
        // Read xml files and shows them as text.
        fetch('/parser/parse_xml')
            .then(function(response) {
                // Is successful request?
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(function(data) {
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
                // Fetch request failure error.
                errorsDiv.innerHTML = 'There has been a problem with your fetch operation:', error;
            });
        };
        
        // Parse xml data and transfers it to the database.
        fetch('/parser/xml_to_db')
            .then(function(response) {
                // Is successful request?
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(function(data) {
              const parsedData = JSON.parse(data);
              if (parsedData.success) {
                xmlText();
              } else {
                errorsDiv.innerHTML = parsedData.message;
              }
            })
            .catch(function(error) {
                // Fetch request failure error.
                errorsDiv.innerHTML = 'There has been a problem with your fetch operation:', error;
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