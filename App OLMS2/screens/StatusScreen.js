import React, { Component } from 'react';
import { StackNavigator } from "react-navigation";

import Liststatus from './status/Liststatus';

const AppNavigator = StackNavigator(
  {
    Liststatus: { screen: Liststatus },
  },
  {
    navigationOptions: () => ({
      header: null,
    }),
  }
);

export default class StatusScreen extends React.Component {
  constructor(props){
    super(props);
  }
  render() {
      return (
          <AppNavigator />
      );
  }
}
