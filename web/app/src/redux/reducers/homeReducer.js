const homeReducer = (state = {data: [], ddd:11, paging : {currentPage:0} }, action) => {
    switch (action.type) {
        case 'APP_DATA_LOADING_SUCCESS':
            return {
                data: action.reset ? action.data : state.data.concat(action.data),
                paging : action.paging
            }
        case 'APP_DATA_LOADING_FAILURE':
            return {
                data: state.data.concat(action.data)
            }
        default:
            return state
    }
}

export default homeReducer