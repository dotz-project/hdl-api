import { createStore, applyMiddleware} from 'redux';
import { rootReducers } from './reducers';
import thunk from 'redux-thunk';
export const reduxStore = createStore(rootReducers,applyMiddleware(thunk));