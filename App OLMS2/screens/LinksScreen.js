import React, { Component } from 'react';
import {View, Dimensions} from 'react-native';
import { StackNavigator, navigationOptions} from 'react-navigation';

import MenuSubject from './Subject/MenuSubject';
import SubjectScreen from './Subject/SubjectScreen';
import LeaveformScreen from './Leave/LeaveformScreen';

const SubjectScreenRouter = StackNavigator(
    {   
        MenuSubject: { screen: MenuSubject },
        SubjectScreen: { screen: SubjectScreen },
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
                <SubjectScreenRouter />
        );
    }
}
