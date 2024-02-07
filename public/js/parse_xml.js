document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("xml_parse_btn").addEventListener("click", function() {
      const errorsDiv = document.getElementById('parser_errors');
      
      let publishXml = function () {
        // Read xml files and shows them as text.
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
              const parsedXmlAsText = parsedData.data.parsed_xml_as_text;
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
        
        publishXml();
    });

    // Modal window closing.
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