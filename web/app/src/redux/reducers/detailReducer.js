const detailReducer = (state = {data: {} }, action) => {
    switch (action.type) {
        case 'APP_DATA_DETAIL_SUCCESS':
            return {
                ...state,
                data : action.data
            }
        case 'APP_DATA_DETAIL_FAILURE':
            return {
                ...state,
                data: action.data
            }
        default:
            return state
    }
}

export default detailReducer




