import React, { Component } from 'react';
import { connect } from 'react-redux';
import LoaderScreen from './LoaderScreen';
import HomeScreen from './HomeScreen';
import DetailScreen from './DetailScreen';
import './App.css';
import { gotoScreen, dataRequest, detailRequest} from './redux/actions/AppActions'


class App extends Component {
    
    constructor(props) {
        super(props);
        this.hashchange = this.hashchange.bind(this);
    }

    componentDidMount() {
        window.addEventListener("hashchange", this.hashchange, false);
        this.router()
    }
    componentWillUnmount() {
        window.removeEventListener("hashchange", this.hashchange, false);
    }
    
    hashchange(){
        this.router()
    }

    router(){
        var hash = window.location.hash;
        if (!hash) 
            window.location.hash = '#11';
        var args = hash.split('/');
        var ddd = args[0].replace('#', '');
        if (args[1]) {
            this.props.detailRequest(args[1]);
            this.props.gotoScreen('detail', { ddd: ddd, id: args[1] });
        } else {
            this.props.dataRequest(ddd, 0, 3);
            this.props.gotoScreen('home', { ddd: ddd });
        }
        window.scrollTo(0, 0);
    }

    render() {
        switch (this.props.screen){
            case "detail":
                return (<div><DetailScreen /><LoaderScreen /></div>);
            case "home":
                return (<div><HomeScreen /> <LoaderScreen /></div >);
            default:
                return (<LoaderScreen />);
        }
    }
}

export default connect(
    (state) => {
        return ({
            screen : state.appReducer.screen,
            isLoading: state.appReducer.isLoading
        })
    }, (dispatch, ownProps) => ({
        gotoScreen: (screen, params) => dispatch(gotoScreen(screen, params)),
        dataRequest: (ddd, page, perPage) => dispatch(dataRequest(ddd, page, perPage, true)),
        detailRequest: (id) => dispatch(detailRequest(id))
   })
)(App);

