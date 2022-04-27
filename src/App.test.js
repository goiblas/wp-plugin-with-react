import { render, screen } from '@testing-library/react';
import App from './App';

test('should be render text', () => {
  render(<App name="Text from React" />);
  const linkElement = screen.getByText(/Text from React/i);
  expect(linkElement).toBeInTheDocument();
});
