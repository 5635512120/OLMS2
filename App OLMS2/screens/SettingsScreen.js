import React, { Component } from 'react';
import {View, Dimensions} from 'react-native';
import { StackNavigator, navigationOptions} from 'react-navigation';

import MenuSubject from './Leave/MenuSubject';
import LeaveformScreen from './Leave/LeaveformScreen';

const LeaveScreenRouter = StackNavigator(
    {   
        MenuSubject: { screen: MenuSubject },
        LeaveformScreen: { screen: LeaveformScreen },
    },
    {
      navigationOptions: () => ({
        header: null,
      }),
    }
)
export default class HomeScreen extends React.Component {
    render() {
        return (
                <LeaveScreenRouter />
        );
    }
}
