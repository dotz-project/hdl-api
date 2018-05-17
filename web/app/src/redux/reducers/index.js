import { combineReducers } from 'redux'

import appReducer from './appReducer'
import homeReducer from './homeReducer'
import detailReducer from './detailReducer'


export const rootReducers = combineReducers({
    appReducer,
    homeReducer,
    detailReducer
})