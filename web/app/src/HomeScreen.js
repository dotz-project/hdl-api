import React, { Component } from 'react';
import './App.css';
import { connect } from 'react-redux';
import { dataRequest } from './redux/actions/AppActions'


class HomeScreen extends Component {
    
    constructor(props){
        super(props)
        this.loadMore = this.loadMore.bind(this);
    }

    componentDidMount() {
        console.log('Home:componentDidMount')
    }
    componentWillUnmount() {
        console.log('Home:componentWillUnmount')
    }
    
    loadMore(e){
        e.preventDefault();
        console.log("loadMore")
        console.log(this.props);
        this.props.dataRequest(this.props.ddd, this.props.paging.currentPage, 3);
    }

    render() {
        const { data } = this.props;
        return (
            <div className="Home">
                {data && data.map((item, idx) => (
                    <div key={item.id} className="Home-intem">
                            <div className="Home-item-title">
                                <i class="arrow right" />
                                <i class="arrow right" />
                                &nbsp;&nbsp;
                                {item.title}
                            </div>
                            <div className="Home-item-description">
                                {item.description}
                            </div>
                        <div className="Home-item-rule" >
                            <span className="Home-item-rule-clique">
                                <a href={"#" + this.props.ddd + "/" + item.id} >
                                    CLIQUE E SAIBA MAIS
                                </a>
                            </span>
                        </div>
                    </div>
                ))}
                { (this.props.paging.currentPage < this.props.paging.pageCount) &&
                <button className="Home-loadmore" onClick={this.loadMore}>carregar mais</button>
                }
                
            </div>
        )
    }
}


export default connect(
    (state) => {
        console.log(state)
        return ({
            data: state.homeReducer.data,
            paging: state.homeReducer.paging,
            ddd: state.appReducer.ddd
        })
    }, (dispatch, ownProps) => ({
        dataRequest: (ddd, page, perPage) => dispatch(dataRequest(ddd, page, perPage, false))
    })
)(HomeScreen);