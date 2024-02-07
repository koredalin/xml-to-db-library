<?php require_once  'layouts/header.php'; ?>

<div class="default-container">
    <h1><?php echo $title; ?></h1>

    <button id="xml_parse_btn">Parse XML input</button>
    
    <div>
        <span><input type="text" name="author_name_search" id="author_name_search" ></span>
        <span><button id="author_name_search_submit">Author Search</button></span>
    </div>
    
    <div id="books_list"></div>
    <div id="books_list_errors"></div>
</div>

<!-- XML input text modal window. -->
<div id="xml_text_modal" class="modal">
    <pre>
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="xml_text"></div>
        </div>
        <div id="parser_errors"></div>
    </pre>
</div>

<script src="js/parse_xml.js"></script>
<script src="js/books_list.js"></script>

<?php require_once 'layouts/footer.php';
