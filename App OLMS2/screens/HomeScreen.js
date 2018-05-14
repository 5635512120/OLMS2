import React, { Component } from 'react';
import {View, Dimensions} from 'react-native';
import { StackNavigator, navigationOptions} from 'react-navigation';

import MainhomeScreen from './Home/MainhomeScreen';
import SubjectScreen from './Subject/SubjectScreen';
import News from './Home/News';
import LeaveformScreen from './Leave/LeaveformScreen';


export default class HomeScreen extends React.Component {
    constructor(props){
        super(props);
        
    }
    render() {
        const HomeScreenRouter = StackNavigator(
            {   
                MainhomeScreen: { screen: props => <MainhomeScreen logout={this.props.handleLogout} {...props}/>},
                SubjectScreen: { screen: SubjectScreen },
                NewScreen: { screen: News },
                LeaveformScreen: { screen: LeaveformScreen },
            },
            {
              navigationOptions: () => ({
                header: null,
              }),
            }
        )
        return (
                <HomeScreenRouter />
        );
    }
}

