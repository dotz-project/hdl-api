import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux'
import {reduxStore} from './redux/store'

import './index.css';
import App from './App';
import registerServiceWorker from './registerServiceWorker';



ReactDOM.render(<Provider store={reduxStore}><App /></Provider>, document.getElementById('root'));
registerServiceWorker();
