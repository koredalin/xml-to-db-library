document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("author_name_search_submit").addEventListener("click", function() {
      const errorsDiv = document.getElementById('books_list_errors');
      const authorSearchInput = document.getElementById('author_name_search');
      if (authorSearchInput.value.length === 0) {
        errorsDiv.innerHTML = 'Please, insert an author name slug.';
        return;
      } else {
        errorsDiv.innerHTML = '';
      }

      let searchAuthor = function () {
        // Read xml files and shows them as text.
        fetch('/data/search_by_author?author_name=' + authorSearchInput.value)
            .then(function(response) {
                // Is successful request?
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(function(data) {
              errorsDiv.innerHTML = '';
              const parsedData = JSON.parse(data);
              const books = parsedData.data;
              fillBooksHtml(books);
            })
            .catch(function(error) {
                // Fetch request failure error.
                errorsDiv.innerHTML = 'There has been a problem with your fetch operation:' + error;
            });
        };
        
        let fillBooksHtml = function(books) {
          let booksContainerDiv = document.getElementById('books_list');
          let delay = 0.5;
          Object.values(books).forEach(book => {
            let rowDiv = document.createElement("div");
            let authorNameSpan = document.createElement("span");
            let bookNameSpan = document.createElement("span");
            rowDiv.classList.add('book-container');
            rowDiv.style['animation-delay'] = delay + 's';
            delay += 0.5;
            authorNameSpan.classList.add('author-name');
            bookNameSpan.classList.add('book-title');
            authorNameSpan.innerHTML = book.author_name;
            bookNameSpan.innerHTML = book.book_title;
            rowDiv.appendChild(authorNameSpan);
            rowDiv.appendChild(bookNameSpan);
            booksContainerDiv.appendChild(rowDiv);
          });
        };
        
        searchAuthor();
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


//document.getElementById("author_name_search_submit").addEventListener("click", executeSearch);
//
//// Прикрепяне на функцията към keyup събитието на текстовото поле
//document.getElementById("author_name_search").addEventListener("keyup", function(event) {
//    // Проверка дали натиснатият клавиш е Enter
//    if (event.key === "Enter") {
//        executeSearch(); // Изпълнение на функцията, ако е натиснат Enter
//    }
//});