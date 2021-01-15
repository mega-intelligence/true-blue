require('./bootstrap');

require('alpinejs');

let dropArea = $('.drop-area');
let draggableItem = $('.draggable-item');

draggableItem.on('dragstart', (e) => {
    console.log('item being dragged');
    e.originalEvent.dataTransfer.setData("dragged_category_id", $(e.target).data('dragged-category-id'));
});

dropArea.on('drop', (e) => {
    e.preventDefault();
    let data = e.originalEvent.dataTransfer.getData("dragged_category_id");
    console.log('category ' + data + ' dropped into ' + $(e.target).data('drop-area-category-id'));
});

dropArea.on('dragend', (e) => {
    // hideDropAreas();
});


dropArea.on('dragover', (e) => {
    e.preventDefault();
    $(e.target).removeClass('border-dashed');
    $(e.target).addClass('border-solid');
    console.log('dragging over');
});

dropArea.on('dragleave', (e) => {
    e.preventDefault();
    $(e.target).removeClass('border-solid');
    $(e.target).addClass('border-dashed');
    console.log('dragging leave');
});

function hideDropAreas() {
    $('.drop-area').hide();
}

function showDropAreas() {
    $('.drop-area').show();
}

