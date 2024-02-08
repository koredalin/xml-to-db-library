<?php require_once  'layouts/header.php'; ?>

<div class="default-container">
    <h1><?php echo $title; ?></h1>

    <div class="controls">
        <div class="control-group"><button id="xml_parse_btn" class="parse-btn" >Parse XML input</button></div>
        <div class="control-group">
            <input type="text" name="author_name_search" id="author_name_search" >
            <button id="author_name_search_submit" class="search-btn">Author Search</button>
        </div>
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
