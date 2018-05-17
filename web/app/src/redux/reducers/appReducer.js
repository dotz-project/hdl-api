const appReducer = (state = { screen: 'loader', isLoading: true, ddd:11 }, action) => {
    switch (action.type) {
        case 'APP_GOTO_HOME':
            return {
                ...state,
                screen : 'home',
                ddd : action.param.ddd,
                data : []
            }
        case 'APP_GOTO_DETAIL':
            return {
                ...state,
                screen: 'detail',
                ddd: action.param.ddd,
                id: action.param.id
            }
        case 'APP_LOADING' :
            return {
                ...state,
                isLoading : true
            }
        case 'APP_LOADING_SUCCESS' :
        case 'APP_LOADING_FAILURE' :
            return {
                ...state,
                isLoading : false
            }
        default:
            return state
    }
}

export default appReducer