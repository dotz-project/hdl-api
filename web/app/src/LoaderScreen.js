import React, { Component } from 'react';
import './App.css';
import { gotoScreen } from './redux/actions/AppActions'
import { connect } from 'react-redux';

class LoaderScreen extends Component {

    render ( ){
        if(this.props.isLoading){
            return (<div className="loading">Loading...</div>)
        }else{
            return (<div></div>)
        }
    }

}


export default connect(
    (state) => {
        return ({
            isLoading: state.appReducer.isLoading
        })
    }, (dispatch, ownProps) => ({
        gotoScreen: (screen, params) => dispatch(gotoScreen(screen, params)),
    })
)(LoaderScreen);
