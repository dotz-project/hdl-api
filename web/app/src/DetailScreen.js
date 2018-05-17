import React, { Component } from 'react';
import ReactSwipe from 'react-swipe';
import './App.css';
import { connect } from 'react-redux';

class DetailScreen extends Component {

    constructor(props) {
        super(props);
        this.handleClick = this.handleClick.bind(this);
    }

    handleClick(e){
        e.preventDefault();
        window.location.hash = '#'+this.props.ddd
    }

    componentDidMount() {
        console.log('Detail:componentDidMount')
    }
    componentWillUnmount() {
        console.log('Detail:componentWillUnmount')
    }
    
    render() {
        const { data } = this.props;
        var images = []
        var maps = []
        data.handoutSheets && data.handoutSheets.map(item => (
            images.push((<img key={'sheets_' + item.id} src={item.image_lg} useMap={"#imagemap_" + item.id} alt={item.label} title={item.label} />))
        ))
        var areas = [((<area key={'area_' + 0} shape="rect" coords="0,0,82,126" href="sun.htm" alt="Sun" />))];        
        data.handoutSheets && data.handoutSheets.map(item => (
            maps.push((<map key={'imagemap_' + item.id} name={"imagemap_" + item.id} >{areas}</map>))    
        ))
        let rdnkey = Math.random();
        return (
            <div style={{ width: '100vw' }}>
                <ReactSwipe key={rdnkey} ref={reactSwipe => this.reactSwipe = reactSwipe} className="mySwipe" swipeOptions={{ continuous: false }}>
                    {images}
                </ReactSwipe>
                {maps}
                <button className="Detail-backbutton" onClick={this.handleClick}>
                    Voltar
                </button>
            </div>
        )
    }
}
export default connect(
    (state) => {
        return ({
            data: state.detailReducer.data,
            ddd: state.appReducer.ddd
        })
    }, (dispatch, ownProps) => ({
    })
)(DetailScreen);