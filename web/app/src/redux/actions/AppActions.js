import { 
    APP_LOADING,
    APP_LOADING_SUCCESS,
    APP_LOADING_FAILURE,
    
    APP_DATA_LOADING_SUCCESS,
    APP_DATA_LOADING_FAILURE,
    
    APP_DATA_DETAIL_SUCCESS,
    APP_DATA_DETAIL_FAILURE,
    
    APP_GOTO_HOME,
    APP_GOTO_DETAIL
} from '../constants';

var HOST;

if (window.location.href.indexOf("localhost") > -1) {
    HOST = "http://localhost:8000/apiv1";
} else {
    HOST = "http://api.cms-claro.homolog.mkmtecnologia.com.br/apiv1";
}

export const dataRequest = (ddd, page, perPage, reset) => {
    var paging;
    return dispatch => {

        dispatch({ type: APP_LOADING });
        page++;
        fetch(HOST + "/handouts/all?ddd=" + ddd + "&page=" + page + "&sort=-started_at&per-page="+perPage) // + window.location.hash.replace('#', ''))
            .then(function (response) {
                console.log(response)
                console.log(response.url)        //=> String
                console.log(response.status)     //=> number 100–599
                console.log(response.statusText) //=> String
                console.log(response.headers.get('X-Pagination-Current-Page'))    //=> Headers
                console.log(response.headers.get('X-Pagination-Page-Count'))    //=> Headers
                console.log(response.headers.get('X-Pagination-Per-Page'))    //=> Headers
                console.log(response.headers.get('X-Pagination-Total-Count'))    //=> Headers
                console.log(response.headers.get('X-Rate-Limit-Limit'))    //=> Headers
                console.log(response.headers.get('X-Rate-Limit-Remaining'))    //=> Headers
                console.log(response.headers.get('X-Rate-Limit-Reset'))    //=> Headers
                paging = {
                    "currentPage" : response.headers.get('X-Pagination-Current-Page'),
                    "pageCount" : response.headers.get('X-Pagination-Page-Count'),
                    "perPage" : response.headers.get('X-Pagination-Per-Page'),
                    "totalCount" : response.headers.get('X-Pagination-Total-Count')
                }
                return response
            })
            .then(res  => res.json())
            .then(
                (result) => {
                    console.log(paging)
                    dispatch({ type: APP_LOADING_SUCCESS });
                    dispatch({ type: APP_DATA_LOADING_SUCCESS, data: result, paging: paging, reset : reset });
                },
                (error) => {
                    dispatch({ type: APP_LOADING_FAILURE });
                    dispatch({ type: APP_DATA_LOADING_FAILURE, error: error });
                }
            )
    }
}

export const detailRequest = (id) => {
    return dispatch => {
        dispatch({ type: APP_LOADING });
        fetch(HOST + "/handouts/detail?id=" + id + "&expand=handoutSheets")
            .then(res => res.json())
            .then(
                (result) => {
                    dispatch({ type: APP_LOADING_SUCCESS });
                    dispatch({ type: APP_DATA_DETAIL_SUCCESS, data: result });
                },
                (error) => {
                    dispatch({ type: APP_LOADING_FAILURE });
                    dispatch({ type: APP_DATA_DETAIL_FAILURE, error: error });
                }
            )
    }
}

export const gotoScreen = (screen, param) => {
    return dispatch => {
        switch(screen){
            case "detail":
                dispatch({ type: APP_GOTO_DETAIL, param });
                break;
            case "home":
            default:
                dispatch({ type: APP_GOTO_HOME, param });
                break;
        }
    }
}