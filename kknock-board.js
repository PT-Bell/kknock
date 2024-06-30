function toggleSort(currentSort, currentOrder, column) {
    let newOrder = 'asc';
    if (currentSort === column && currentOrder === 'asc') {
        newOrder = 'desc';
    }
    window.location.href = `?sort=${column}&order=${newOrder}`;
}