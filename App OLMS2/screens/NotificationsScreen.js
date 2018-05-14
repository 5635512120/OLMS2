import React, { Component } from 'react';
import {View, Dimensions} from 'react-native';
import { StackNavigator, navigationOptions} from 'react-navigation';

import MenuSubject from './Leave/MenuSubject';
import LeaveformScreen from './Leave/LeaveformScreen';
import BellScreen from './Notifications/BellScreen';
import SubjectScreen from './Subject/SubjectScreen';

const NotificationScreenRouter = StackNavigator(
    {   
        BellScreen: { screen: BellScreen },
        SubjectScreen: { screen: SubjectScreen },
        LeaveformScreen: { screen: LeaveformScreen },
    },
    {
      navigationOptions: () => ({
        header: null,
      }),
    }
)
export default class NotificationsScreen extends React.Component {
    render() {
        return (
                <NotificationScreenRouter />
        );
    }
}
